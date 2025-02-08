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
                                        @clear="fetchTransactionEntries"
                                        placeholder="Select Year"
                                    />
                                </div>

                                <!-- Custom Date Range -->
                                <div
                                    class="col-auto d-flex gap-2"
                                    v-if="selectedFilter === 'Custom'"
                                >
                                    <input
                                        type="date"
                                        class="form-control"
                                        id="date"
                                        v-model="startDate"
                                         
                                        placeholder="Start Date"
                                    />
                                    <input
                                        type="date"
                                        class="form-control"
                                        id="date"
                                        v-model="endDate"
                                         
                                        placeholder="End Date"
                                    />
                                </div>

                                <div class="col-auto">
                                    <button
                                        @click="fetchTransactionEntries"
                                        class="btn btn-success"
                                        :disabled="serachingLoading"
                                    >
                                        <span
                                            v-if="serachingLoading"
                                            class="spinner-border spinner-border-sm"
                                            role="status"
                                            aria-hidden="true"
                                        ></span>
                                        <span v-if="!serachingLoading"
                                            >Search</span
                                        >
                                    </button>
                                </div>
                            </div>

                            <!-- Export Buttons -->
                            <div class="btn-group" role="group">
                                <!-- <button
                                    class="btn btn-primary"
                                    title="Download as Excel"
                                    @click="exportToExcel"
                                    :disabled="excelBtnLoader"
                                >
                                    <span
                                        v-if="excelBtnLoader"
                                        class="spinner-border spinner-border-sm"
                                        role="status"
                                        aria-hidden="true"
                                    ></span>
                                    <span v-if="!excelBtnLoader">
                                        <i class="bi bi-file-earmark-excel"></i
                                    ></span>
                                </button> -->
                                <button
                                    class="btn btn-danger"
                                    title="Download as PDF"
                                    @click="exportToPDF"
                                    :disabled="pdfBtnLoader"
                                >
                                    <span
                                        v-if="pdfBtnLoader"
                                        class="spinner-border spinner-border-sm"
                                        role="status"
                                        aria-hidden="true"
                                    ></span>
                                    <span v-if="!pdfBtnLoader">
                                        <i class="bi bi-file-earmark-pdf"></i
                                    ></span>
                                </button>
                                <!-- <button
                                    class="btn btn-secondary"
                                    title="Print"
                                    @click="printSlip"
                                >
                                    <i class="bi bi-printer"></i>
                                </button> -->
                            </div>
                        </div>
                        <span class="text-danger" v-if="FilterErrors">
                            {{ FilterErrors }}
                        </span>
                    </div>

                    <!-- Income Statement Summary -->

                    <div v-if="transactionEntries.length">
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

            FilterErrors: "",
            serachingLoading: false,
            pdfBtnLoader: false,
            excelBtnLoader: false,
        };
    },
    mounted() {
        this.fetchTransactionEntries();
    },
    methods: {
        fetchTransactionEntries() {
            this.serachingLoading = true;
            this.FilterErrors = "";

            // Validation checks
            if (
                this.selectedFilter === "Monthly" &&
                (!this.selectedMonth || !this.selectedYear)
            ) {
                this.FilterErrors =
                    "Please select both Month and Year for the Monthly filter.";
                this.serachingLoading = false;
                return;
            }

            if (this.selectedFilter === "Yearly" && !this.selectedYear) {
                this.FilterErrors =
                    "Please select a Year for the Yearly filter.";
                this.serachingLoading = false;
                return;
            }

            if (
                this.selectedFilter === "Custom" &&
                (!this.startDate || !this.endDate)
            ) {
                this.FilterErrors =
                    "Please select both Start Date and End Date for the Custom filter.";
                this.serachingLoading = false;
                return;
            }

            let formData = new FormData();
            formData.append("selectedFilter", this.selectedFilter);

            if (this.selectedMonth) {
                formData.append("selectedMonth", this.selectedMonth);
            }
            if (this.selectedYear) {
                formData.append("selectedYear", this.selectedYear);
            }
            if (this.startDate) {
                formData.append("startDate", this.startDate);
            }
            if (this.endDate) {
                formData.append("endDate", this.endDate);
            }

            axios
                .post(
                    route("api.transaction.income.statements.fetch"),
                    formData,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }
                )
                .then((response) => {
                    this.serachingLoading = false;
                    console.log(response.data);
                    // Updating the data properly
                    this.transactionEntries =
                        response.data.transactionEntries || [];
                    this.totalIncome = response.data.totalIncome || 0;
                    this.totalExpense = response.data.totalExpense || 0;
                    this.totalProfit = response.data.totalProfit || 0;
                })
                .catch((error) => {
                    this.serachingLoading = false;
                    console.error(error);
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

       // Export to PDF
       exportToPDF() {
            this.pdfBtnLoader = true;
            let formData = new FormData();

            formData.append("selectedFilter", this.selectedFilter);

            if (this.selectedMonth) {
                formData.append("selectedMonth", this.selectedMonth);
            }
            if (this.selectedYear) {
                formData.append("selectedYear", this.selectedYear);
            }
            if (this.startDate) {
                formData.append("startDate", this.startDate);
            }
            if (this.endDate) {
                formData.append("endDate", this.endDate);
            }

            axios
                .post(route("download-income-statement-pdf"), formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                    responseType: "blob", // Important for handling PDF response
                })
                .then((response) => {
                    // Create a link element
                    const link = document.createElement("a");
                    // Create a URL for the blob
                    const url = window.URL.createObjectURL(
                        new Blob([response.data])
                    );
                    link.href = url;
                    // Set the file name for the download
                    link.setAttribute("download", "TransactionReport.pdf");
                    // Append the link to the body, click it to trigger download, and then remove it
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    this.pdfBtnLoader = false;
                })
                .catch((error) => {
                    this.pdfBtnLoader = false;
                    toastr.error(
                        error.response?.data?.message || "Error generating PDF"
                    );
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
