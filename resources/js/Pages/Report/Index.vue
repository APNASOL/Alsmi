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
                                    class="col-auto d-flex gap-2"
                                    v-if="selectedFilter === 'Monthly'"
                                >
                                    <!-- Year Dropdown for Monthly -->
                                    <div class="col-auto">
                                        <Multiselect
                                            v-model="selectedYear"
                                            :options="yearsOptions"
                                            :searchable="true"
                                            @select="applyFilter"
                                            @clear="fetchTransactionEntries"
                                            placeholder="Select Year"
                                        />
                                    </div>

                                    <!-- Month Dropdown for Monthly -->
                                    <div class="col-auto">
                                        <Multiselect
                                            v-model="selectedMonth"
                                            :options="monthsOptions"
                                            :searchable="true"
                                            @select="applyFilter"
                                            @clear="fetchTransactionEntries"
                                            placeholder="Select Month"
                                        />
                                    </div>
                                </div>

                                <!-- Yearly Filter -->
                                <div
                                    class="col-auto"
                                    v-if="selectedFilter === 'Yearly'"
                                >
                                    <Multiselect
                                        v-model="selectedYear"
                                        :options="yearsOptions"
                                        :searchable="true"
                                        @select="applyFilter"
                                        @clear="fetchTransactionEntries"
                                        placeholder="Select Year"
                                    />
                                </div>

                                <!-- Custom Date Range -->
                                <div
                                    class="col-auto d-flex gap-2"
                                    v-if="selectedFilter === 'Custom'"
                                >
                                    <Datepicker
                                        autoApply
                                        :enableTimePicker="false"
                                        id="fromDate"
                                        v-model="startDate"
                                        @update:model-value="applyFilter"
                                        placeholder="Start Date"
                                    />
                                    <Datepicker
                                        autoApply
                                        :enableTimePicker="false"
                                        id="toDate"
                                        v-model="endDate"
                                        @update:model-value="applyFilter"
                                        placeholder="End Date"
                                    />
                                </div>
                            </div>

                            <!-- Export Buttons -->
                            <div
                                class="btn-group"
                                role="group"
                                v-if="filteredEntries.length"
                            >
                                <button
                                    class="btn btn-primary"
                                    title="Download as Excel"
                                    @click="exportToExcel"
                                >
                                    <i class="bi bi-file-earmark-excel"></i>
                                </button>
                                <button
                                    class="btn btn-danger"
                                    title="Download as PDF"
                                    @click="exportToPDF"
                                >
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </button>
                                <button
                                    class="btn btn-secondary"
                                    title="Print"
                                    @click="printSlip"
                                >
                                    <i class="bi bi-printer"></i>
                                </button>
                            </div>
                        </div>
                        <span class="text-danger" v-if="FilterErrors">
                            {{ FilterErrors }}
                        </span>
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
                        <p class="text-center text-danger">
                            No data available.
                        </p>
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
            selectedFilter: "Yearly",
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
            FilterErrors: "",
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
            const filter = this.selectedFilter;

            if (filter === "Monthly") {
                // Validation: Check if both Month and Year are selected
                if (!this.selectedMonth || !this.selectedYear) {
                    this.FilterErrors =
                        "Both Year and Month are required for Monthly filter.";
                    return; // Stop further execution if validation fails
                }

                this.FilterErrors = "";

                // Calculate monthly report
                this.filteredEntries = this.calculateMonthlyReport(
                    this.selectedMonth,
                    this.selectedYear
                );
            } else if (filter === "Yearly") {
                // Validation: Check if Year is selected
                if (!this.selectedYear) {
                    this.FilterErrors = "Year is required for Yearly filter.";
                    return; // Stop further execution if validation fails
                }

                this.FilterErrors = "";

                // Calculate yearly report
                this.filteredEntries = this.calculateYearlyReport(
                    this.selectedYear
                );
            } else if (filter === "Custom") {
                // Validation: Check if both Start and End Dates are selected
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
                } else {
                    this.FilterErrors =
                        "Both Start and End Dates are required for Custom filter.";
                    return; // Stop further execution if validation fails
                }

                this.FilterErrors = "";
            } else {
                // Reset to all entries if no filter is applied
                this.filteredEntries = this.transactionEntries;
            }

            // Calculate totals
            this.calculateTotals();
        },

        // Print Slip
        printSlip() {
            let printWindow = window.open("", "_blank");

            // Title
            let title = "All Transactions List"; // Default title
            if (this.selectedFilter === "Monthly" && this.selectedMonth) {
                const monthName = this.monthsOptions.find(
                    (m) => m.value === this.selectedMonth
                ).label;
                title = `Transactions for ${monthName} ${this.selectedYear}`;
            } else if (this.selectedFilter === "Yearly" && this.selectedYear) {
                title = `Transactions for the Year ${this.selectedYear}`;
            } else if (
                this.selectedFilter === "Custom" &&
                this.startDate &&
                this.endDate
            ) {
                title = `Transactions from ${this.formatDate(
                    this.startDate
                )} to ${this.formatDate(this.endDate)}`;
            }

            // HTML Content
            printWindow.document.write(`
                <html>
                <head>
                    <title>Transaction Slip</title>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        h2 { text-align: center; margin-bottom: 20px; }
                        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                        th, td { border: 1px solid black; padding: 8px; text-align: left; }
                        th { background-color: #f2f2f2; }
                        .footer { text-align: right; font-size: 12px; margin-top: 20px; }
                    </style>
                </head>
                <body>
                    <h2>${title}</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Income</th>
                                <th>Expense</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${this.filteredEntries
                                .map(
                                    (entry, index) => `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${entry.date}</td>
                                    <td>${this.formatCurrency(
                                        entry.income
                                    )}</td>
                                    <td>${this.formatCurrency(
                                        entry.expense
                                    )}</td>
                                    <td>${this.formatCurrency(
                                        entry.balance
                                    )}</td>
                                </tr>
                            `
                                )
                                .join("")}
                        </tbody>
                    </table>
                    <div class="footer">Printed: ${new Date().toLocaleString(
                        "en-US",
                        {
                            day: "2-digit",
                            month: "short",
                            year: "numeric",
                            hour: "2-digit",
                            minute: "2-digit",
                            hour12: true,
                        }
                    )}</div>
                </body>
                </html>
            `);

            // Finish loading the content before printing
            printWindow.document.close();
            printWindow.onload = function () {
                printWindow.print();
                printWindow.close();
            };
        },

        // Export to Excel
        exportToExcel() {
            const ws = XLSX.utils.json_to_sheet(
                this.filteredEntries.map((entry) => ({
                    Date: entry.date,
                    Income: entry.income,
                    Expense: entry.expense,
                    Balance: entry.balance,
                }))
            );
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Transactions");
            XLSX.writeFile(wb, "transactions.xlsx");
        },

        // Export to PDF
        exportToPDF() {
            const doc = new jsPDF();

            // Title
            let title = "All Transactions List"; // Default title
            if (this.selectedFilter === "Monthly" && this.selectedMonth) {
                const monthName = this.monthsOptions.find(
                    (m) => m.value === this.selectedMonth
                ).label;
                title = `Transactions for ${monthName} ${this.selectedYear}`;
            } else if (this.selectedFilter === "Yearly" && this.selectedYear) {
                title = `Transactions for the Year ${this.selectedYear}`;
            } else if (
                this.selectedFilter === "Custom" &&
                this.startDate &&
                this.endDate
            ) {
                title = `Transactions from ${this.formatDate(
                    this.startDate
                )} to ${this.formatDate(this.endDate)}`;
            }

            doc.setFont("helvetica", "bold");
            doc.setFontSize(18);
            doc.text(title, doc.internal.pageSize.getWidth() / 2, 20, {
                align: "center",
            });

            // Table Data
            const rows = this.filteredEntries.map((entry) => [
                entry.date,
                this.formatCurrency(entry.income),
                this.formatCurrency(entry.expense),
                this.formatCurrency(entry.balance),
            ]);

            // Add Table
            doc.autoTable({
                head: [["Date", "Income", "Expense", "Balance"]],
                body: rows,
                startY: 30,
            });

            // Footer
            const printedText = `Printed: ${new Date().toLocaleString("en-US", {
                day: "2-digit",
                month: "short",
                year: "numeric",
                hour: "2-digit",
                minute: "2-digit",
                hour12: true,
            })}`;

            const pageWidth = doc.internal.pageSize.getWidth();
            const pageHeight = doc.internal.pageSize.getHeight();

            doc.setFontSize(10);
            doc.text(printedText, pageWidth - 60, pageHeight - 10); // Bottom-right

            // Save PDF
            doc.save("transactions.pdf");
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

        calculateBalance(index) {
            let balance = 0;
            for (let i = 0; i <= index; i++) {
                const entry = this.transactionEntries[i];
                const cashIn = parseFloat(entry.cash_in) || 0; // Ensure it's a number
                const cashOut = parseFloat(entry.cash_out) || 0; // Ensure it's a number
                balance += cashIn;
                balance -= cashOut;
            }
            return this.formatCurrency(balance);
        },

        // Helper function to format dates properly
        formatDate(date) {
            const d = new Date(date);
            return d.toLocaleDateString("en-US", {
                day: "2-digit",
                month: "short",
                year: "numeric",
            });
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
