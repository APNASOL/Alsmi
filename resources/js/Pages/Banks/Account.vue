<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1 class="theme-text-color">Accounts</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/dashboard">R & R Coal</a>
                        </li>
                        <li class="breadcrumb-item">Accounts</li>
                        <li class="breadcrumb-item active">Index</li>
                    </ol>
                </nav>
            </div>
        </div>

        <section class="section">
            
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title theme-text-color">Account Ledger</h5>

                    <!-- PDF Download Button -->

                    <div class="filters mb-3">
                        <div class="row">
                            <!-- Filter Type Dropdown using Multiselect -->
                            <div class="col-md-4">
                                <label for="filterType">Filter By</label>
                                <Multiselect
                                    v-model="filterType"
                                    :options="filterOptions"
                                    :placeholder="'Select Filter Type'"
                                    :searchable="true"
                                    @change="resetFilters"
                                    :class="{
                                        'invalid-bg': formErrors.filterType,
                                    }"
                                />
                            </div>

                            <!-- Month and Year Fields -->
                            <div
                                class="col-md-3"
                                v-if="filterType === 'monthYear'"
                            >
                                <label for="month">Month</label>
                                <Multiselect
                                    v-model="filters.month"
                                    :options="months"
                                    :placeholder="'Select Month'"
                                    :searchable="true"
                                    @update:model-value="fetchDetails"
                                />
                                <div
                                    class="invalid-feedback animated fadeIn"
                                    v-if="formErrors.month"
                                >
                                    {{ formErrors.month[0] }}
                                </div>
                            </div>
                            <div
                                class="col-md-3"
                                v-if="
                                    filterType === 'monthYear' ||
                                    filterType === 'yearOnly'
                                "
                            >
                                <label for="year">Year</label>
                                <Multiselect
                                    v-model="filters.year"
                                    :options="years"
                                    :placeholder="'Select Year'"
                                    :searchable="true"
                                    @update:model-value="fetchDetails"
                                />
                                <div
                                    class="invalid-feedback animated fadeIn"
                                    v-if="formErrors.year"
                                >
                                    {{ formErrors.year[0] }}
                                </div>
                            </div>

                            <!-- Date Range Fields -->
                            <div
                                class="col-md-3"
                                v-if="filterType === 'dateRange'"
                            >
                                <label for="fromDate">From Date</label>
                                <Datepicker
                                    autoApply
                                    :enableTimePicker="false"
                                    id="fromDate"
                                    v-model="filters.fromDate"
                                    @update:model-value="fetchDetails"
                                />
                                <div
                                    class="invalid-feedback animated fadeIn"
                                    v-if="formErrors.fromDate"
                                >
                                    {{ formErrors.fromDate[0] }}
                                </div>
                            </div>
                            <div
                                class="col-md-3"
                                v-if="filterType === 'dateRange'"
                            >
                                <label for="toDate">To Date</label>
                                <Datepicker
                                    autoApply
                                    :enableTimePicker="false"
                                    id="toDate"
                                    v-model="filters.toDate"
                                    @update:model-value="fetchDetails"
                                />
                                <div
                                    class="invalid-feedback animated fadeIn"
                                    v-if="formErrors.toDate"
                                >
                                    {{ formErrors.toDate[0] }}
                                </div>
                            </div>

                            <!-- Search Button with Spinner -->
                            <div class="col-md-2">
                                <button
                                    class="btn btn-primary mt-4"
                                    @click="handleSearch"
                                    :disabled="isLoading"
                                >
                                    <span
                                        v-if="isLoading"
                                        class="spinner-border spinner-border-sm"
                                        role="status"
                                        aria-hidden="true"
                                    ></span>
                                    <span v-else>Search</span>
                                </button>
                            </div>
                        </div>
                    </div>
<hr>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Consumer Name</th>
                                    <th scope="col">Process Type</th>
                                    <th scope="col">Notes</th>
                                    <th scope="col">Cash In</th>
                                    <th scope="col">Cash Out</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Ref.No</th>
                                    <th scope="col">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(entry, index) in Accounts"
                                    :key="index"
                                >
                                    <th scope="row">{{ index + 1 }}</th>
                                    <td>
                                        {{ entry.consumer_name }} <br />
                                        <small
                                            >{{ entry.bank_title }} <br />
                                            {{ entry.bank_account }}</small
                                        >
                                    </td>
                                    <td>{{ entry.process_type }}</td>
                                    <td>{{ entry.note }}</td>
                                    <td
                                        :title="
                                            convertToWords(entry.cash_in || 0)
                                        "
                                    >
                                        {{ formatNumber(entry.cash_in || 0) }}
                                    </td>
                                    <td
                                        :title="
                                            convertToWords(entry.cash_out || 0)
                                        "
                                    >
                                        {{ formatNumber(entry.cash_out || 0) }}
                                    </td>

                                    <td>{{ formatDate(entry.date) }}</td>
                                    <td>{{ entry.ref_no || "-" }}</td>
                                    <td :title="convertToWords(entry.balance)">
                                        {{ formatNumber(entry.balance) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end mb-1" v-if="Accounts && Accounts.length>0">
                            <button
                                v-if="!pdfBtnSpinner"
                                @click="downloadPDF"
                                class="btn btn-primary mt-3 ml-3"
                            >
                                <i class="bi bi-file-earmark-pdf"></i> Download PDF
                            </button>
                            <button
                                v-else
                                class="btn btn-success"
                                type="button"
                                disabled
                            >
                                <span class="spinner-border spinner-border-sm"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</template>

<script>
import Master from "../Layout/Master.vue";
import axios from "axios";
import Multiselect from "@vueform/multiselect";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import jsPDF from "jspdf";
import "jspdf-autotable";

export default {
    layout: Master,
    props: ["customerId"],
    components: {
        Multiselect,
        Datepicker,
    },
    created() {
        this.fetchDetails();
    },
    data() {
        const currentYear = new Date().getFullYear();
        return {
            isLoading: false,
            Accounts: [], // Stores account entries with balance
            filters: {
                month: "",
                year: "",
                fromDate: "",
                toDate: "",
            },
            filterType: "monthYear", // Default filter type
            filterOptions: [
                { value: "monthYear", label: "Month and Year" },
                { value: "yearOnly", label: "Year Only" },
                { value: "dateRange", label: "Date Range" },
            ],
            months: [
                { value: "01", label: "January" },
                { value: "02", label: "February" },
                { value: "03", label: "March" },
                { value: "04", label: "April" },
                { value: "05", label: "May" },
                { value: "06", label: "June" },
                { value: "07", label: "July" },
                { value: "08", label: "August" },
                { value: "09", label: "September" },
                { value: "10", label: "October" },
                { value: "11", label: "November" },
                { value: "12", label: "December" },
            ],
            years: Array.from({ length: 10 }, (_, i) => currentYear - i), // Last 10 years
            formErrors: {},
            pdfBtnSpinner: false,
        };
    },
    methods: {
        resetFilters() {
            this.filters.month = "";
            this.filters.year = "";
            this.filters.fromDate = "";
            this.filters.toDate = "";
        },
        handleSearch() {
            this.isLoading = true; // Show spinner
            this.fetchDetails();
        },

        // Fetch account details
        fetchDetails() {
            const params = {
                month:
                    this.filterType === "monthYear" ? this.filters.month : "",
                year: this.filterType !== "dateRange" ? this.filters.year : "",
                fromDate:
                    this.filterType === "dateRange"
                        ? this.filters.fromDate
                        : "",
                toDate:
                    this.filterType === "dateRange" ? this.filters.toDate : "",
            };

            axios
                .get(route("api.fetch.account.details"), { params })
                .then((response) => {
                    let runningBalance = 0;
                    this.Accounts = response.data.map((entry) => {
                        const cashIn = entry.cash_in || 0;
                        const cashOut = entry.cash_out || 0;
                        runningBalance += cashIn - cashOut;
                        return {
                            ...entry,
                            cash_in: cashIn,
                            cash_out: cashOut,
                            balance: runningBalance,
                        };
                    });
                })
                .catch((error) => {
                    toastr.error("Error fetching account details.");
                })
                .finally(() => {
                    this.isLoading = false; // Hide spinner
                });
        },

        // Format date (convert to proper format)
        formatDate(date) {
            return new Date(date).toLocaleDateString();
        },

        // Format numbers with commas
        formatNumber(value) {
            if (value == null) return "-";
            return value.toLocaleString();
        },

        // Convert numbers to words (optional logic)
        convertToWords(number) {
            if (number == null) return "No balance";
            // Add logic to convert number to words if needed
            return number.toString(); // For simplicity
        },

        // PDF Download Logic
        downloadPDF() {
            this.pdfBtnSpinner = true;
            const doc = new jsPDF();
            
            // Title of the document
            doc.setFontSize(18);
            doc.setTextColor(40);
            doc.text("Account Ledger Details", 14, 20);

            // Table data generation
            const tableData = this.Accounts.map((entry, index) => [
                index + 1,
                entry.consumer_name,
                entry.process_type,
                entry.note || "-",
                this.formatNumber(entry.cash_in),
                this.formatNumber(entry.cash_out),
                this.formatDate(entry.date),
                entry.ref_no || "-",
                this.formatNumber(entry.balance),
            ]);

            // Add AutoTable to the document
            doc.autoTable({
                startY: 30,
                head: [
                    [
                        "#",
                        "Consumer Name",
                        "Process Type",
                        "Notes",
                        "Cash In",
                        "Cash Out",
                        "Date",
                        "Ref.No",
                        "Balance",
                    ],
                ],
                body: tableData,
                styles: {
                    fontSize: 10,
                    halign: "center",
                },
                headStyles: {
                    fillColor: [0, 0, 0], // Header background color (black)
                    textColor: [255, 255, 255], // White text color
                },
            });

            // Save the generated PDF
            doc.save("Account_Ledger.pdf");
            this.pdfBtnSpinner = false;
        },
    },
};
</script>
