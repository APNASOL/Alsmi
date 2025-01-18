<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1 class="theme-text-color">Accounts</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/dashboard">Alsmi</a>
                        </li>
                        <li class="breadcrumb-item">Accounts</li>
                        <li class="breadcrumb-item active">Index</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button
                    class="btn btn-success mt-3"
                    data-bs-toggle="modal"
                    data-bs-target="#bankmodal"
                    @click="
                        clearFields();
                        processFor('Cash In');
                    "
                >
                    <i class="bi bi-plus-lg"></i> Cash In
                </button>
                <button
                    class="btn btn-success mt-3 ms-1"
                    data-bs-toggle="modal"
                    data-bs-target="#bankmodal"
                    @click="
                        clearFields();
                        processFor('Cash Out');
                    "
                >
                    <i class="bi bi-dash-lg"></i> Cash Out
                </button>
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
                                    class="btn btn-success mt-4"
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
                    <hr />
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
                        <div
                            class="d-flex justify-content-end mb-1"
                            v-if="Accounts && Accounts.length > 0"
                        >
                            <button
                                v-if="!pdfBtnSpinner"
                                @click="downloadPDF"
                                class="btn btn-success mt-3 ml-3"
                            >
                                <i class="bi bi-file-earmark-pdf"></i> Download
                                PDF
                            </button>
                            <button
                                v-else
                                class="btn btn-success"
                                type="button"
                                disabled
                            >
                                <span
                                    class="spinner-border spinner-border-sm"
                                ></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Bank Modal -->
        <div
            class="modal fade"
            id="bankmodal"
            tabindex="-1"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary">
                            {{ form.process_for }}
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-body p-3">
                            <div class="row g-3">
                                <div class="col-md-6 col-12">
                                    <label for="consumer" class="form-label"
                                        >Choose Type</label
                                    >
                                    <Multiselect
                                        v-model="form.consumer"
                                        :options="consumerOptions"
                                        :placeholder="'Process choose type'"
                                        :searchable="true"
                                        @select="
                                            fetchConsumersList(form.consumer)
                                        "
                                        :class="{
                                            'invalid-bg': formErrors.consumer,
                                        }"
                                    />
                                    <div
                                        v-if="formErrors.consumer"
                                        class="invalid-feedback"
                                    >
                                        {{ formErrors.consumer[0] }}
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="consumer_id" class="form-label"
                                        >Available {{ form.consumer }}'s
                                        List</label
                                    >
                                    <Multiselect
                                        v-model="form.consumer_id"
                                        :options="consumerList"
                                        :placeholder="'Select name'"
                                        :searchable="true"
                                        :class="{
                                            'invalid-bg':
                                                formErrors.consumer_id,
                                        }"
                                    />
                                    <div
                                        v-if="formErrors.consumer_id"
                                        class="invalid-feedback"
                                    >
                                        {{ formErrors.consumer_id[0] }}
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="bank_id" class="form-label"
                                        >Bank</label
                                    >
                                    <Multiselect
                                        v-model="form.bank_id"
                                        :options="banksOptions"
                                        :placeholder="'Select Bank'"
                                        :searchable="true"
                                        :class="{
                                            'invalid-bg': formErrors.bank_id,
                                        }"
                                    />
                                    <div
                                        v-if="formErrors.bank_id"
                                        class="invalid-feedback"
                                    >
                                        {{ formErrors.bank_id[0] }}
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label
                                        for="transaction_type"
                                        class="form-label"
                                        >Transaction type</label
                                    >
                                    <Multiselect
                                        v-model="form.transaction_type"
                                        :options="transaction_typeOptions"
                                        :placeholder="'Select Bank'"
                                        :searchable="true"
                                        :class="{
                                            'invalid-bg':
                                                formErrors.transaction_type,
                                        }"
                                    />
                                    <div
                                        v-if="formErrors.transaction_type"
                                        class="invalid-feedback"
                                    >
                                        {{ formErrors.transaction_type[0] }}
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="amount" class="form-label"
                                        >amount</label
                                    >
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="amount"
                                        v-model="form.amount"
                                        :class="{
                                            'invalid-bg': formErrors.amount,
                                        }"
                                    />
                                    <div
                                        v-if="formErrors.amount"
                                        class="invalid-feedback"
                                    >
                                        {{ formErrors.amount[0] }}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <label for="ref_no" class="form-label"
                                        >Ref.No</label
                                    >
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="ref_no"
                                        v-model="form.ref_no"
                                        :class="{
                                            'invalid-bg': formErrors.ref_no,
                                        }"
                                    />
                                    <div
                                        v-if="formErrors.ref_no"
                                        class="invalid-feedback"
                                    >
                                        {{ formErrors.ref_no[0] }}
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="account" class="form-label"
                                        >Account #</label
                                    >
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="account"
                                        v-model="form.account"
                                        :class="{
                                            'invalid-bg': formErrors.account,
                                        }"
                                    />
                                    <div
                                        v-if="formErrors.account"
                                        class="invalid-feedback"
                                    >
                                        {{ formErrors.account[0] }}
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label
                                        for="current_amount"
                                        class="form-label"
                                        >Current Amount</label
                                    >
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="current_amount"
                                        v-model="form.current_amount"
                                        :class="{
                                            'invalid-bg':
                                                formErrors.current_amount,
                                        }"
                                    />
                                    <div
                                        v-if="formErrors.current_amount"
                                        class="invalid-feedback"
                                    >
                                        {{ formErrors.current_amount[0] }}
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="date" class="form-label"
                                        >Date</label
                                    >
                                    <input
                                        type="date"
                                        class="form-control"
                                        id="date"
                                        v-model="form.date"
                                        :class="{
                                            'invalid-bg': formErrors.date,
                                        }"
                                    />
                                    <!-- <small class="text-muted">
                                            Date must be before starting order entry date
                                        </small> -->
                                    <div
                                        v-if="formErrors.date"
                                        class="invalid-feedback"
                                    >
                                        {{ formErrors.date[0] }}
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button
                                        :disabled="!formStatus"
                                        class="btn btn-success"
                                        @click="submit"
                                    >
                                        Save
                                        <span
                                            v-if="!formStatus"
                                            class="spinner-border spinner-border-sm"
                                        ></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button
                hidden
                data-bs-toggle="modal"
                data-bs-target="#customermodal"
                ref="closeModal"
            ></button>
        </div>
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
        this.fetchBanks();
        this.fetchConsumersList(this.form.consumer);
    },
    data() {
        const currentYear = new Date().getFullYear();
        return {
            form: {
                id: "",
                process_for: "",
                consumer: "Customer",
                consumer_id: "",
                bank_id: "",
                transaction_type: "",
                amount: "",
                ref_no: "",
                account: "",
                current_amount: "",
                date: "",
            },

            consumerOptions: ["Customer", "Supplier", "Partner"],
            transaction_typeOptions: ["Online", "Cash"],
            banksOptions: [],
            consumerList: [],
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
            formStatus: true,
        };
    },
    methods: {
        processFor(processFor) {
            this.form.process_for = processFor;
        },
        fetchConsumersList(consumer_type) {
            axios
                .get(route("api.fetch.consumers", { consumer_type })) // Pass consumer_type as a parameter
                .then((response) => {
                    this.consumerList = response.data; // Update the consumer list with API response
                    this.form.consumer_id = Object.keys(response.data)[0];
                })
                .catch((error) => {
                    toastr.error("Error fetching account details."); // Show error notification
                    console.error(error); // Optional: Log the error for debugging
                });
        },
        fetchBanks() {
            axios
                .get(route("api.bank.pluck"))
                .then((response) => {
                    this.form.bank_id = Object.keys(response.data)[0];
                    this.banksOptions = response.data;
                })
                .catch((error) => {
                    toastr.error("Error fetching account details."); // Show error notification
                    console.error(error); // Optional: Log the error for debugging
                });
        },
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
        clearFields() {},
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

        submit() {
            this.formStatus = false; // Show spinner (disable button)

            axios
                .post(route("api.account.data.store"), this.form) // Send form data to the backend using the named route
                .then(() => {
                    this.formStatus = true; // Enable button (hide spinner)
                    // Perform any additional actions like fetching updated data
                    // For example: this.fetchAccounts(); // if you want to refresh the list of accounts

                    toastr.success("Account record saved successfully."); // Show success message
                    this.$refs.closeModal?.click(); // Close the modal if you have a reference to it
                })
                .catch((error) => {
                    this.formStatus = true; // Enable button (hide spinner)
                    this.formErrors = error.response.data.errors || {}; // Store form validation errors

                    // You can display error messages as required
                    toastr.error(
                        "There was an error saving the account record."
                    );
                });
        },
    },
};
</script>
