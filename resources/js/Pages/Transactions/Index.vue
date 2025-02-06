<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1 class="theme-text-color">Transaction</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/dashboard">Alsmi</a>
                        </li>
                        <li class="breadcrumb-item">Transaction</li>
                        <li class="breadcrumb-item active">Index</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button
                    class="btn btn-success mt-3"
                    data-bs-toggle="modal"
                    data-bs-target="#transactionmodal"
                    @click="clearFields"
                >
                    <i class="bi bi-plus-lg"></i> New Transaction
                </button>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title theme-text-color">
                        All Transaction Entries
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
                                <input
                                            type="date"
                                            class="form-control"
                                            id="date"
                                            v-model="startDate"
                                            :class="{
                                                'invalid-bg': formErrors.date,
                                            }"
                                            @update:model-value="applyFilter"
                                            placeholder="Start Date"
                                        />
                                <input
                                            type="date"
                                            class="form-control"
                                            id="date"
                                            v-model="endDate"
                                            :class="{
                                                'invalid-bg': formErrors.date,
                                            }"
                                            @update:model-value="applyFilter"
                                            placeholder="End Date"
                                        />
                                    
                                    
                                </div>
                            </div>

                            <!-- Export Buttons -->
                            <div class="btn-group" role="group">
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
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Reciept No</th>
                                    <th scope="col">Descriptions</th>
                                    <th scope="col">Method</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Cash In</th>
                                    <th scope="col">Cash Out</th>
                                    <th scope="col">Balance</th>
                                    <th scope="col">Reciept</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(entry, index) in filteredEntries"
                                    :key="entry.id"
                                >
                                    <th scope="row">{{ index + 1 }}</th>
                                    <td>{{ entry.transaction_date }}</td>
                                    <td>{{ entry.ref_no }}</td>
                                    <td>{{ entry.remarks }}</td>
                                    <td>{{ entry.method }}</td>
                                    <td>
                                        {{
                                            entry.income_type ??
                                            entry.expense_type
                                        }}
                                    </td>
                                    <td>
                                        {{ formatCurrency(entry.cash_in) ?? 0 }}
                                    </td>
                                    <td>
                                        {{
                                            formatCurrency(entry.cash_out) ?? 0
                                        }}
                                    </td>
                                    <td>{{ calculateBalance(index) }}</td>
                                    <td>
                                        <ImageZooming
                                            v-if="entry.cash_out"
                                            :file="'https://images.examples.com/wp-content/uploads/2017/05/Receipt-for-Employee-Salary.jpg'"
                                            :width="100"
                                        />
                                        <ImageZooming
                                            v-if="entry.cash_in"
                                            :file="'https://cdn.prod.website-files.com/65383059db0c80e24b49540f/653afaf743e0dc4cc11a5a02_Receipt-OCR-Taggun-demo.png'"
                                            :width="100"
                                        />
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <!-- <button
                                                class="btn btn-sm fs-6"
                                                title="Edit"
                                                data-bs-toggle="modal"
                                                data-bs-target="#transactionmodal"
                                                @click="
                                                    showEntry(entry.id);
                                                    clearFields();
                                                "
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <DeleteModal
                                                :deleteId="entry.id"
                                                @deleteThis="deleteThis"
                                            ></DeleteModal> -->
                                            <i
                                                class="bi bi-pencil me-2"
                                                @click="warning"
                                            ></i>
                                            <i
                                                class="bi bi-trash"
                                                @click="warning"
                                            ></i>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Transaction Modal -->
            <div
                class="modal fade"
                id="transactionmodal"
                tabindex="-1"
                aria-labelledby="exampleModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-primary" v-if="form.id">
                                {{ form.remarks }} - {{ form.method }}
                                <small>({{ form.date }})</small>
                            </h5>
                            <h5 class="modal-title text-primary" v-else>
                                New Transaction Entry
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
                                    <div class="col-12 col-md-12 mb-3">
                                        <label>{{ "Process Type" }} </label>
                                        <Multiselect
                                            v-model="form.process_type"
                                            :options="processTypeOptions"
                                            :searchable="true"
                                            @select="clearProcessType"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.process_type,
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.process_type"
                                        >
                                            {{ formErrors.process_type[0] }}
                                        </div>
                                    </div>

                                    <div
                                        class="col-12 col-md-6"
                                        v-if="form.process_type == 'Income'"
                                    >
                                        <label>{{ "Income Type" }} </label>
                                        <Multiselect
                                            v-model="form.income_type"
                                            :options="IncomeTypesOpions"
                                            :searchable="true"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.income_type,
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.income_type"
                                        >
                                            {{ formErrors.income_type[0] }}
                                        </div>
                                    </div>

                                    <div
                                        class="col-md-6 col-12"
                                        v-if="form.process_type == 'Income'"
                                    >
                                        <label for="cash_in" class="form-label"
                                            >Cash In</label
                                        >
                                        <input
                                            type="number"
                                            class="form-control"
                                            id="cash_in"
                                            v-model="form.cash_in"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.cash_in,
                                            }"
                                        />
                                        <div
                                            v-if="formErrors.cash_in"
                                            class="invalid-feedback"
                                        >
                                            {{ formErrors.cash_in[0] }}
                                        </div>
                                    </div>

                                    <div
                                        class="col-12 col-md-6"
                                        v-if="form.process_type == 'Expense'"
                                    >
                                        <label>{{ "Expense Type" }} </label>
                                        <Multiselect
                                            v-model="form.expense_type"
                                            :options="ExpansTypesOpions"
                                            :searchable="true"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.expense_type,
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.expense_type"
                                        >
                                            {{ formErrors.expense_type[0] }}
                                        </div>
                                    </div>

                                    <div
                                        class="col-md-6 col-12"
                                        v-if="form.process_type == 'Expense'"
                                    >
                                        <label for="cash_out" class="form-label"
                                            >Cash Out</label
                                        >
                                        <input
                                            type="number"
                                            class="form-control"
                                            id="cash_out"
                                            v-model="form.cash_out"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.cash_out,
                                            }"
                                        />
                                        <div
                                            v-if="formErrors.cash_out"
                                            class="invalid-feedback"
                                        >
                                            {{ formErrors.cash_out[0] }}
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <label for="remarks" class="form-label"
                                            >Description</label
                                        >
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="remarks"
                                            v-model="form.remarks"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.remarks,
                                            }"
                                        />
                                        <div
                                            v-if="formErrors.remarks"
                                            class="invalid-feedback"
                                        >
                                            {{ formErrors.remarks[0] }}
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label>{{ "Payment Method" }} </label>
                                        <Multiselect
                                            v-model="form.method"
                                            :options="methodTypesOpions"
                                            :searchable="true"
                                            :class="{
                                                'invalid-bg': formErrors.method,
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.method"
                                        >
                                            {{ formErrors.method[0] }}
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <label for="type" class="form-label"
                                            >Reciept No</label
                                        >
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="type"
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
                                        <div
                                            v-if="formErrors.date"
                                            class="invalid-feedback"
                                        >
                                            {{ formErrors.date[0] }}
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label
                                            for="receipt_image"
                                            class="form-label"
                                            >Receipt image</label
                                        >
                                        <br />
                                        <CropperOffCanvas
                                            @croppedImg="croppedImgPassToForm"
                                            accept=".jpg,.jpeg,.png"
                                        />

                                        <br />

                                        <ImageZooming
                                            v-if="form.receipt_image"
                                            :file="form.receipt_image"
                                            :width="100"
                                        />
                                        <ImageZooming
                                            v-else
                                            :file="existing_receipt_image"
                                            :width="100"
                                        />

                                        <div
                                            v-if="formErrors.date"
                                            class="invalid-feedback"
                                        >
                                            {{ formErrors.receipt_image[0] }}
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <button
                                            type="submit"
                                            class="btn btn-success"
                                            v-if="formStatus === 1"
                                            @click="submit"
                                        >
                                            Save
                                        </button>
                                        <button
                                            class="btn btn-success"
                                            type="button"
                                            disabled
                                            v-else
                                        >
                                            Saving
                                            <span
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
    created() {
        // this.fetchTransactionEntries();
        this.pluckExpansTypes();
        this.pluckIncomeTypes();
        this.process_type = "Income";
    },
    components: {
        Multiselect,
        Datepicker,
    },
    data() {
        return {
            filteredEntries: [],
            selectedFilter: "",
            selectedMonth: "",
            selectedYear: "",
            startDate: "",
            endDate: "",
            months: [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December",
            ],
            years: Array.from(
                { length: 10 },
                (_, i) => new Date().getFullYear() - i
            ),

            transactionEntries: [],
            form: {
                id: "",
                cash_in: "",
                cash_out: "",
                date: "",
                ref_no: "",
                method: "",
                remarks: "",
                expense_type: "",
                income_type: "",
                process_type: "",
                receipt_image: "",
            },
            formErrors: [],
            formStatus: 1, // 1 = ready, 0 = saving
            isCashInReadonly: false,
            isCashOutReadonly: false,
            ExpansTypesOpions: [],
            IncomeTypesOpions: [],
            methodTypesOpions: ["Bank", "Cash"],
            processTypeOptions: ["Expense", "Income"],
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
            yearsOptions: [
                2024, 2025, 2026, 2027, 2028, 2029, 2030, 2031, 2032, 2033,
                2034, 2035,
            ],
            existing_receipt_image: "",
            FilterErrors: "",
            pdfBtnLoader:true,
        };
    },
    mounted() {
        this.fetchTransactionEntries();
    },

    methods: {
        clearProcessType() {
            this.form.cash_in = "";
            this.form.cash_out = "";
        },

        // fetchTransactionEntries() {
        //     axios
        //         .get(route("api.transaction.fetch"))
        //         .then((response) => {
        //             this.transactionEntries = response.data;
        //             console.log(this.transactionEntries);
        //         })
        //         .catch((error) => {
        //             console.error(error);
        //         });
        // },
        fetchTransactionEntries() {
            axios
                .get(route("api.transaction.fetch"))
                .then((response) => {
                    this.transactionEntries = response.data;
                    this.filteredEntries = response.data; // Default to all entries
                })
                .catch((error) => {
                    console.error(error);
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
                // Filter by both Year and Month
                this.filteredEntries = this.transactionEntries.filter(
                    (entry) => {
                        const entryDate = new Date(entry.transaction_date);
                        return (
                            entryDate.getFullYear() ===
                                parseInt(this.selectedYear) &&
                            entryDate.getMonth() + 1 ===
                                parseInt(this.selectedMonth)
                        );
                    }
                );
            } else if (filter === "Yearly") {
                // Validation: Check if  Year are selected
                if (!this.selectedYear) {
                    this.FilterErrors = "Year is required for Yearly filter.";
                    return; // Stop further execution if validation fails
                }
                this.FilterErrors = "";
                // Filter by Year
                this.filteredEntries = this.transactionEntries.filter(
                    (entry) => {
                        return (
                            new Date(entry.transaction_date).getFullYear() ===
                            parseInt(this.selectedYear)
                        );
                    }
                );
            } else if (filter === "Custom") {
                // Validation: Check if  Year are selected
                if (!this.startDate || !this.endDate) {
                    this.FilterErrors =
                        "Both Date are required for Custom filter.";
                    return; // Stop further execution if validation fails
                }

                this.FilterErrors = "";
                // Filter by Custom Date Range
                const start = new Date(this.startDate);
                const end = new Date(this.endDate);
                this.filteredEntries = this.transactionEntries.filter(
                    (entry) => {
                        const date = new Date(entry.transaction_date);
                        return date >= start && date <= end;
                    }
                );
            } else {
                // Reset to all entries if no filter is applied
                this.filteredEntries = this.transactionEntries;
            }
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

        submit() {
            let formData = new FormData();

            formData.append("id", this.form.id);
            formData.append("cash_in", this.form.cash_in);
            formData.append("cash_out", this.form.cash_out);
            formData.append("date", this.form.date);
            formData.append("ref_no", this.form.ref_no);
            formData.append("method", this.form.method);
            formData.append("remarks", this.form.remarks);
            formData.append("expense_type", this.form.expense_type);
            formData.append("income_type", this.form.income_type);
            formData.append("process_type", this.form.process_type);

            // Append image only if it exists
            if (this.form.receipt_image) {
                formData.append("receipt_image", this.form.receipt_image);
            }

            this.formStatus = 0;
            axios
                .post(route("api.transaction.store"), formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then(() => {
                    this.formStatus = 1;
                    this.fetchTransactionEntries();
                    toastr.success("Transaction entry saved successfully.");
                    this.$refs.closeModal?.click();
                })
                .catch((error) => {
                    this.formStatus = 1;
                    toastr.error(error.response.data.message);
                    this.formErrors = error.response.data.errors;
                    console.log(this.formErrors);
                });
        },

        // Calculate running balance dynamically based on the previous entries

        formatCurrency(value) {
            return new Intl.NumberFormat("en-PK", {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            }).format(value);
        },

        clearFields() {
            this.form = {
                id: "",
                remarks: "",

                cash_in: "",
                cash_out: "",

                date: "",
            };
            this.formErrors = [];
        },
        showEntry(entry_id) {
            axios
                .get(route("api.transaction.show", entry_id))
                .then((response) => {
                    this.form = { ...response.data };
                    // this.existing_receipt_image = response.data.receipt_image;
                    // this.form.receipt_image = response.data.receipt_image;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        deleteThis(id) {
            axios
                .delete(route("api.transaction.delete", id))
                .then(() => {
                    this.fetchTransactionEntries();
                    toastr.success("Transaction entry deleted successfully.");
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        pluckExpansTypes() {
            axios
                .get(route("api.income.pluck"))
                .then((response) => {
                    this.IncomeTypesOpions = response.data;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        pluckIncomeTypes() {
            axios
                .get(route("api.expense.pluck"))
                .then((response) => {
                    this.ExpansTypesOpions = response.data;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        // Export data to Excel
        exportToExcel() {
            const ws = XLSX.utils.json_to_sheet(
                this.filteredEntries.map((entry) => ({
                    Date: entry.transaction_date,
                    Descriptions: entry.remarks,
                    Method: entry.method,
                    Type: entry.expense_type ?? entry.income_type,
                    "Cash In": entry.cash_in ?? 0,
                    "Cash Out": entry.cash_out ?? 0,
                    Balance: this.calculateBalance(
                        this.filteredEntries.indexOf(entry)
                    ),
                }))
            );
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Transactions");
            XLSX.writeFile(wb, "transactions.xlsx");
        },

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
        .post(route("download-pdf"), formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
            responseType: 'blob', // Important for handling PDF response
        })
        .then(response => {
            // Create a link element
            const link = document.createElement('a');
            // Create a URL for the blob
            const url = window.URL.createObjectURL(new Blob([response.data]));
            link.href = url;
            // Set the file name for the download
            link.setAttribute('download', 'TransactionReport.pdf');
            // Append the link to the body, click it to trigger download, and then remove it
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            this.pdfBtnLoader = false;
        })
        .catch((error) => {
            this.pdfBtnLoader = false;
            toastr.error(error.response?.data?.message || "Error generating PDF");
        });
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
        warning() {
            toastr.warning(
                "This feature is not added yet! Working in progress."
            );
        },
        croppedImgPassToForm(img) {
            this.form.receipt_image = img;
        },
        printSlip() {
            let printWindow = window.open("", "_blank");
            const getMonthName = (monthNumber) => {
                const date = new Date(2025, monthNumber - 1, 1); // Year is arbitrary
                return date.toLocaleString("en-US", { month: "long" });
            };
            let currentDateTime = new Date().toLocaleString("en-US", {
                day: "2-digit",
                month: "short",
                year: "numeric",
                hour: "2-digit",
                minute: "2-digit",
                hour12: true,
            });

            let title = "All Transactions List"; // Default title
            if (this.selectedFilter === "Monthly") {
                const monthName = getMonthName(this.selectedMonth);
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
                        <th>Receipt No</th>
                        <th>Descriptions</th>
                        <th>Method</th>
                        <th>Type</th>
                        <th>Cash In</th>
                        <th>Cash Out</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    ${this.filteredEntries
                        .map(
                            (entry, index) => `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${entry.transaction_date}</td>
                            <td>${entry.ref_no}</td>
                            <td>${entry.remarks}</td>
                            <td>${entry.method}</td>
                            <td>${entry.income_type ?? entry.expense_type}</td>
                            <td>${this.formatCurrency(entry.cash_in) ?? 0}</td>
                            <td>${this.formatCurrency(entry.cash_out) ?? 0}</td>
                            <td>${this.calculateBalance(index)}</td>
                        </tr>
                    `
                        )
                        .join("")}
                </tbody>
            </table>
            <div class="footer">Printed: ${currentDateTime}</div>
        </body>
        </html>
    `);

            printWindow.document.close();
            printWindow.onload = function () {
                printWindow.print();
                printWindow.close();
            };
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
