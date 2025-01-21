<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1 class="theme-text-color">Reports</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/dashboard">Alsmi</a>
                        </li>
                        <li class="breadcrumb-item">Reports</li>
                        <li class="breadcrumb-item active">Index</li>
                    </ol>
                </nav>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title theme-text-color">
                        All Transaction & Reports
                    </h5>
                    <!-- Filter Section -->
                    <div class="card card-body p-2">
                        <div
                            class="d-flex justify-content-between align-items-center c-filter"
                        >
                            <!-- Filters Section -->
                            <div class="d-flex align-items-center gap-2">
                                <!-- Main Filter -->
                                <div class="col-auto">
                                    <Multiselect
                                        v-model="selectedFilter"
                                        :options="[
                                            'Monthly',
                                            'Yearly',
                                            'Custom',
                                        ]"
                                        :searchable="true"
                                        @select="applyFilter"
                                        placeholder="Filter By"
                                    />
                                </div>

                                <!-- Monthly Filter -->
                                <div
                                    v-if="selectedFilter === 'Monthly'"
                                    class="col-auto"
                                >
                                    <Multiselect
                                        v-model="selectedMonth"
                                        :options="monthsOptions"
                                        :searchable="true"
                                        @select="applyFilter"
                                        placeholder="Select Month"
                                    />
                                </div>

                                <!-- Yearly Filter -->
                                <div
                                    v-if="selectedFilter === 'Yearly'"
                                    class="col-auto"
                                >
                                    <Multiselect
                                        v-model="selectedYear"
                                        :options="yearsOptions"
                                        :searchable="true"
                                        @select="applyFilter"
                                        placeholder="Select Year"
                                    />
                                </div>

                                <!-- Custom Date Range -->
                                <div
                                    v-if="selectedFilter === 'Custom'"
                                    class="col-auto d-flex gap-2"
                                >
                                    <Datepicker
                                        autoApply
                                        :enableTimePicker="false"
                                        v-model="startDate"
                                        @update:model-value="applyFilter"
                                        placeholder="Start Date"
                                    />
                                    <Datepicker
                                        autoApply
                                        :enableTimePicker="false"
                                        v-model="endDate"
                                        @update:model-value="applyFilter"
                                        placeholder="End Date"
                                    />
                                </div>
                            </div>

                            <!-- Export Buttons -->
                            <div class="btn-group" role="group" v-if="filteredEntries.length">
                                <button
                                    class="btn btn-primary"
                                    @click="exportToExcel"
                                >
                                    <i class="bi bi-file-earmark-excel"></i>
                                </button>
                                <button
                                    class="btn btn-danger"
                                    @click="exportToPDF"
                                >
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Table Section -->
                    <div class="table-responsive" v-if="filteredEntries.length">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th
                                        scope="col"
                                        v-if="selectedFilter != 'Yearly'"
                                    >
                                        Date
                                    </th>
                                    <th scope="col">Income</th>
                                    <th scope="col">Expense</th>
                                    <th scope="col">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(entry, index) in filteredEntries"
                                    :key="index"
                                >
                                    <th scope="row">{{ index + 1 }}</th>
                                    <td v-if="selectedFilter != 'Yearly'">
                                        {{ entry.date }}
                                    </td>
                                    <td>{{ formatCurrency(entry.income) }}</td>
                                    <td>{{ formatCurrency(entry.expense) }}</td>
                                    <td>{{ formatCurrency(entry.balance) }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th
                                        colspan="0"
                                        v-if="selectedFilter == 'Yearly'"
                                    >
                                        Total
                                    </th>
                                    <th v-else colspan="2">Total</th>
                                    <th>{{ formatCurrency(totalIncome) }}</th>
                                    <th>{{ formatCurrency(totalExpense) }}</th>
                                    <th>{{ formatCurrency(totalBalance) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div v-else>
                        <p class="text-center text-danger">No data available.</p>

                    </div>
                </div>
            </div>
        </section>
    </main>
</template>

<script>
import axios from "axios";
import Master from "../Layout/Master.vue";
import Multiselect from "@vueform/multiselect";
import * as XLSX from "xlsx";
import jsPDF from "jspdf";
import "jspdf-autotable";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

export default {
    layout: Master,
    components: {
        Multiselect,
        Datepicker,
    },
    data() {
        return {
            filteredEntries: [],
            selectedFilter: "Monthly",
            selectedMonth: 1,
            selectedYear: 2025,
            startDate: "",
            endDate: "",
            monthsOptions: [
                { value: 1, label: "January" },
                { value: 2, label: "February" },
                { value: 3, label: "March" },
                { value: 4, label: "April" },
                { value: 5, label: "May" },
                { value: 6, label: "June" },
                { value: 7, label: "July" },
                { value: 8, label: "August" },
                { value: 9, label: "September" },
                { value: 10, label: "October" },
                { value: 11, label: "November" },
                { value: 12, label: "December" },
            ],
            yearsOptions: [2020, 2021, 2022, 2023, 2024, 2025],
            transactionEntries: [],
            totalIncome: 0, // Total Income
            totalExpense: 0, // Total Expense
            totalBalance: 0, // Total Balance
        };
    },
    mounted() {
        this.fetchTransactionEntries();
    },
    methods: {
        fetchTransactionEntries() {
            axios
                .get(route("api.transaction.fetch"))
                .then((response) => {
                    console.log("API Response:", response.data); // Debug data
                    this.transactionEntries = response.data;
                    this.filteredEntries = response.data; // Default to all entries
                    this.applyFilter();
                    calculateCustomDateReport();
                })
                .catch((error) => {
                    console.error("Error fetching transactions:", error);
                });
        },
        applyFilter() {
            if (this.selectedFilter === "Monthly" && this.selectedMonth) {
                this.filteredEntries = this.calculateMonthlyReport(
                    this.selectedMonth
                );
            } else if (this.selectedFilter === "Yearly" && this.selectedYear) {
                this.filteredEntries = this.calculateYearlyReport(
                    this.selectedYear
                );
            } else if (this.selectedFilter === "Custom") {
                if (!this.startDate && !this.endDate) {
                    // If no start and end date are selected, show current month's data
                    this.filteredEntries = this.calculateCustomDateReport(
                        this.getCurrentMonthStartDate(),
                        this.getCurrentMonthEndDate()
                    );
                } else if (this.startDate && this.endDate) {
                    // If start and end date are selected, show custom date range data
                    this.filteredEntries = this.calculateCustomDateReport(
                        this.startDate,
                        this.endDate
                    );
                }
            } else {
                this.filteredEntries = this.transactionEntries;
            }

            // Calculate totals
            this.calculateTotals();
        },
        calculateTotals() {
            let totalIncome = 0;
            let totalExpense = 0;
            let totalBalance = 0;

            this.filteredEntries.forEach((entry) => {
                totalIncome += parseFloat(entry.income) || 0;
                totalExpense += parseFloat(entry.expense) || 0;
                totalBalance += parseFloat(entry.balance) || 0;
            });

            // Update total values
            this.totalIncome = totalIncome;
            this.totalExpense = totalExpense;
            this.totalBalance = totalBalance;
        },

        calculateMonthlyReport(month) {
            const monthlyData = [];
            const startOfMonth = new Date(
                new Date().getFullYear(),
                month - 1,
                1
            );
            const endOfMonth = new Date(new Date().getFullYear(), month, 0);

            const groupedByDate = this.transactionEntries.reduce(
                (acc, entry) => {
                    const entryDate = new Date(
                        entry.transaction_date
                    ).toDateString();
                    if (!acc[entryDate]) {
                        acc[entryDate] = [];
                    }
                    acc[entryDate].push(entry);
                    return acc;
                },
                {}
            );

            for (
                let day = startOfMonth.getDate();
                day <= endOfMonth.getDate();
                day++
            ) {
                const currentDate = new Date(
                    new Date().getFullYear(),
                    month - 1,
                    day
                ).toDateString();
                const dailyData = groupedByDate[currentDate] || [];

                let income = 0;
                let expense = 0;

                dailyData.forEach((entry) => {
                    income += parseFloat(entry.cash_in) || 0; // Convert to number or default to 0
                    expense += parseFloat(entry.cash_out) || 0; // Convert to number or default to 0
                });

                const balance = income - expense;

                if (income > 0 || expense > 0) {
                    monthlyData.push({
                        date: currentDate,
                        income: income,
                        expense: expense,
                        balance: balance,
                    });
                }
            }

            return monthlyData;
        },

        calculateYearlyReport(year) {
            // Filter data for the selected year
            const filteredData = this.transactionEntries.filter((entry) => {
                const date = new Date(entry.transaction_date); // Ensure the field name matches your data
                return date.getFullYear() === year;
            });

            console.log("Filtered Data for Year:", filteredData);

            // Aggregate data by month
            const aggregated = filteredData.reduce((acc, entry) => {
                const date = new Date(entry.transaction_date);
                const monthKey = `${date.getFullYear()}-${date.getMonth() + 1}`; // Group by month

                if (!acc[monthKey]) {
                    acc[monthKey] = { income: 0, expense: 0 };
                }

                acc[monthKey].income += Number(entry.cash_in || 0); // Default to 0 if null/undefined
                acc[monthKey].expense += Number(entry.cash_out || 0);

                return acc;
            }, {});

            console.log("Aggregated Data:", aggregated);

            // Map aggregated data into a report format
            const report = Object.keys(aggregated).map((month) => {
                const income = aggregated[month].income;
                const expense = aggregated[month].expense;
                const balance = income - expense; // Ensure balance calculation

                return {
                    date: `${
                        this.monthsOptions[
                            parseInt(month.split("-")[1], 10) - 1
                        ].label
                    } ${year}`,
                    income: income || 0,
                    expense: expense || 0,
                    balance: balance || 0, // Default to 0 if NaN
                };
            });

            console.log("Yearly Report:", report);

            return report;
        },
        calculateCustomDateReport(startDate, endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);

            // Ensure start and end dates are valid
            if (isNaN(start.getTime()) || isNaN(end.getTime())) {
                console.error("Invalid start or end date.");
                return [];
            }

            // Filter transaction entries within the date range
            const filteredData = this.transactionEntries.filter((entry) => {
                const entryDate = new Date(entry.transaction_date);
                return entryDate >= start && entryDate <= end;
            });

            // Generate all days in the range, only including those with transactions
            const report = [];
            let currentDate = new Date(start);

            while (currentDate <= end) {
                const currentDateString = currentDate.toDateString();

                // Find transactions for the current date
                const dailyTransactions = filteredData.filter((entry) => {
                    const entryDate = new Date(entry.transaction_date);
                    return entryDate.toDateString() === currentDateString;
                });

                let income = 0;
                let expense = 0;

                // Aggregate income and expense for the current date
                dailyTransactions.forEach((entry) => {
                    income += parseFloat(entry.cash_in) || 0;
                    expense += parseFloat(entry.cash_out) || 0;
                });

                // Only include days with either income or expense
                if (income > 0 || expense > 0) {
                    const balance = income - expense;

                    // Push the result for the current day
                    report.push({
                        date: currentDateString,
                        income: income,
                        expense: expense,
                        balance: balance,
                    });
                }

                // Move to the next day
                currentDate.setDate(currentDate.getDate() + 1);
            }

            return report;
        },

        getCurrentMonthStartDate() {
            const now = new Date();
            return new Date(now.getFullYear(), now.getMonth(), 1); // Start of the current month
        },

        getCurrentMonthEndDate() {
            const now = new Date();
            return new Date(now.getFullYear(), now.getMonth() + 1, 0); // End of the current month
        },

        aggregateDataByDate(data, groupBy) {
            console.log("Data to Aggregate:", data);

            const aggregated = data.reduce((acc, entry) => {
                const date = new Date(entry.transaction_date);
                const key =
                    groupBy === "month"
                        ? `${date.getFullYear()}-${date.getMonth() + 1}`
                        : date.getFullYear();

                if (!acc[key]) {
                    acc[key] = { income: 0, expense: 0 };
                }

                acc[key].income += Number(entry.cash_in || 0); // Handle NaN by defaulting to 0
                acc[key].expense += Number(entry.cash_out || 0);

                return acc;
            }, {});

            console.log("Aggregated Data:", aggregated);
            return aggregated;
        },
        formatCurrency(value) {
            return new Intl.NumberFormat("en-PK", {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            }).format(value);
        },

        exportToExcel() {
            const ws = XLSX.utils.json_to_sheet(this.filteredEntries);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Transactions");
            XLSX.writeFile(wb, "transactions.xlsx");
        },

        exportToPDF() {
            const doc = new jsPDF();
            doc.setFontSize(18);
            doc.text("Transaction Report", 14, 20);

            const rows = this.filteredEntries.map((entry) => [
                entry.date,
                entry.income,
                entry.expense,
                entry.balance,
            ]);

            doc.autoTable({
                head: [["Date", "Income", "Expense", "Balance"]],
                body: rows,
                startY: 30,
            });

            doc.save("transactions.pdf");
        },
    },
};
</script>

<style scoped>
@import "@vueform/multiselect/themes/default.css";
.invalid-bg {
    border-color: #f8d4d4;
    background-color: #f8d4d4;
}
.invalid-feedback {
    color: red;
    font-size: 0.875rem;
}
.c-filter .multiselect {
    width: 200px !important;
}
</style>
