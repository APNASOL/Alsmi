<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1 class="theme-text-color">Income Statement</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/dashboard">Alsmi</a>
                        </li>
                        <li class="breadcrumb-item">Income Statement</li>
                        <li class="breadcrumb-item active">Index</li>
                    </ol>
                </nav>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title theme-text-color">
                        Income Statement
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
                            <div
                                class="btn-group"
                                role="group"
                                v-if="filteredEntries.length"
                            >
                                <!-- <button
                                    class="btn btn-primary"
                                    @click="exportToExcel"
                                >
                                    <i class="bi bi-file-earmark-excel"></i>
                                </button> -->
                                <button
                                    class="btn btn-danger"
                                    @click="exportToPDF"
                                >
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Income Statement Summary -->
                    <div v-if="filteredEntries.length">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="2">Title</th>
                                    <th colspan="1">Amount</th>
                                </tr>
                                <tr>
                                    <th colspan="2">Income</th>
                                    <td colspan="1" class="text-success">
                                        {{ formatCurrency(totalIncome) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2">Expense</th>
                                    <td colspan="1" class="text-danger">
                                        {{ formatCurrency(totalExpense) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2">Profit</th>
                                    <th
                                        colspan="1"
                                        :class="
                                            totalProfit >= 0
                                                ? 'text-success'
                                                : 'text-danger'
                                        "
                                    >
                                        {{ formatCurrency(totalProfit) }}
                                    </th>
                                </tr>
                            </thead>
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
            selectedFilter: "Monthly",
            selectedMonth: 1,
            selectedYear: new Date().getFullYear(),
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
            totalProfit: 0, // Total Profit
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
                    this.transactionEntries = response.data;
                    this.filteredEntries = response.data; // Default to all entries
                    this.applyFilter();
                })
                .catch((error) => {
                    console.error("Error fetching transactions:", error);
                });
        },

        applyFilter() {
            let filtered = [...this.transactionEntries];

            if (this.selectedFilter === "Monthly") {
                filtered = filtered.filter(
                    (entry) =>
                        new Date(entry.transaction_date).getMonth() + 1 ===
                            this.selectedMonth &&
                        new Date(entry.transaction_date).getFullYear() ===
                            this.selectedYear
                );
            } else if (this.selectedFilter === "Yearly") {
                filtered = filtered.filter(
                    (entry) =>
                        new Date(entry.transaction_date).getFullYear() ===
                        this.selectedYear
                );
            } else if (this.selectedFilter === "Custom") {
                filtered = filtered.filter(
                    (entry) =>
                        new Date(entry.transaction_date) >=
                            new Date(this.startDate) &&
                        new Date(entry.transaction_date) <=
                            new Date(this.endDate)
                );
            }

            this.filteredEntries = filtered;
            this.calculateSummary();
        },

        calculateSummary() {
            this.totalIncome = this.filteredEntries.reduce(
                (acc, entry) => acc + parseFloat(entry.cash_in || 0),
                0
            );
            this.totalExpense = this.filteredEntries.reduce(
                (acc, entry) => acc + parseFloat(entry.cash_out || 0),
                0
            );
            this.totalProfit = this.totalIncome - this.totalExpense;
        },

        formatCurrency(value) {
            return new Intl.NumberFormat("en-PK", {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            }).format(value);
        },

        // exportToExcel() {
        //     const ws = XLSX.utils.json_to_sheet(this.filteredEntries);
        //     const wb = XLSX.utils.book_new();
        //     XLSX.utils.book_append_sheet(wb, ws, "Income Statement");
        //     XLSX.writeFile(wb, "income_statement.xlsx");
        // },

        exportToPDF() {
            const doc = new jsPDF();
            doc.autoTable({
                head: [["Total Income", "Total Expenses", "Net Profit"]],
                body: [
                    [
                        this.formatCurrency(this.totalIncome),
                        this.formatCurrency(this.totalExpense),
                        this.formatCurrency(this.totalProfit),
                    ],
                ],
            });
            doc.save("income_statement.pdf");
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
