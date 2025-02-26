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
                        All Transaction & Reports  {{ selectedFilter }}
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
                                <button
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
                                </button>
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
                    <div
                        class="table-responsive"
                        v-if="transactionEntries.length"
                    >
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">
                                        {{
                                            selectedFilter === "Yearly"
                                                ? "Month"
                                                : "Date"
                                        }}
                                    </th>
                                    <th scope="col">Income</th>
                                    <th scope="col">Expense</th>
                                    <th scope="col">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(entry, index) in transactionEntries"
                                    :key="index"
                                >
                                    <th scope="row">{{ index + 1 }}</th>
                                    <td>{{ entry.date }}</td>
                                    <td>
                                        {{ formatCurrency(entry.income || 0) }}
                                    </td>
                                    <td>
                                        {{ formatCurrency(entry.expense || 0) }}
                                    </td>
                                    <td>
                                        {{ formatCurrency(entry.balance || 0) }}
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th>
                                        {{ formatCurrency(totalIncome || 0) }}
                                    </th>
                                    <th>
                                        {{ formatCurrency(totalExpense || 0) }}
                                    </th>
                                    <th>
                                        {{ formatCurrency(totalBalance || 0) }}
                                    </th>
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
            transactionEntries: [],
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

            totalIncome: 0, // Total Income
            totalExpense: 0, // Total Expense
            totalBalance: 0, // Total Balance

            existing_receipt_image: "",
            FilterErrors: "",
            serachingLoading: false,
            pdfBtnLoader: false,
            excelBtnLoader: false,
            printBtnLoader: false,
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
                .post(route("api.transaction.report.fetch"), formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    this.serachingLoading = false;

                    // Assign data correctly
                    this.transactionEntries = response.data.transactionEntries;
                    this.totalIncome = response.data.totalIncome;
                    this.totalExpense = response.data.totalExpense;
                    this.totalBalance = response.data.totalBalance;
                })
                .catch((error) => {
                    this.serachingLoading = false;
                    console.error(error);
                });
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
                            ${this.transactionEntries
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
    this.excelBtnLoader = true;
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
        .post(route("download-report-excel"), formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
            responseType: "blob", // Important for handling Excel response
        })
        .then((response) => {
            // Create a link element
            const link = document.createElement("a");
            // Create a URL for the blob
            const url = window.URL.createObjectURL(new Blob([response.data]));
            link.href = url;
            // Set the file name for the download
            link.setAttribute("download", "TransactionReport.xlsx");
            // Append the link to the body, click it to trigger download, and then remove it
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            this.excelBtnLoader = false;
        })
        .catch((error) => {
            this.excelBtnLoader = false;
            toastr.error(error.response?.data?.message || "Error generating Excel");
        });
},


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
                .post(route("download-report-pdf"), formData, {
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

        formatCurrency(value) {
            return new Intl.NumberFormat("en-PK", {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            }).format(value);
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
