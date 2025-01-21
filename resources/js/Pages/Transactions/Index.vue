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
    <div class="d-flex justify-content-between align-items-center">
        <!-- Filters Section -->
        <div class="d-flex align-items-center gap-2">
            <!-- Main Filter -->
            <div class="col-auto">
                <Multiselect
                    v-model="selectedFilter"
                    :options="['Monthly', 'Yearly', 'Custom']"
                    :searchable="true"
                    @select="applyFilter"
                    placeholder="Filter By"
                />
            </div>

            <!-- Monthly Filter -->
            <div class="col-auto" v-if="selectedFilter === 'Monthly'">
                <Multiselect
                    v-model="selectedMonth"
                    :options="monthsOptions"
                    :searchable="true"
                    @select="applyFilter"
                    placeholder="Select Month"
                />
            </div>

            <!-- Yearly Filter -->
            <div class="col-auto" v-if="selectedFilter === 'Yearly'">
                <Multiselect
                    v-model="selectedYear"
                    :options="yearsOptions"
                    :searchable="true"
                    @select="applyFilter"
                    placeholder="Select Year"
                />
            </div>

            <!-- Custom Date Range -->
            <div class="col-auto d-flex gap-2" v-if="selectedFilter === 'Custom'">
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
        </div>
    </div>
</div>


                    <!-- Table Section -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Remarks</th>
                                    <th scope="col">Method</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Cash In</th>
                                    <th scope="col">Cash Out</th>
                                    <th scope="col">Balance</th>
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
                                    <td>{{ entry.remarks }}</td>
                                    <td>{{ entry.method }}</td>
                                    <td>
                                        {{
                                            entry.income_type ??
                                            entry.expanse_type
                                        }}
                                    </td>
                                    <td>{{ entry.cash_in ?? 0 }}</td>
                                    <td>{{ entry.cash_out ?? 0 }}</td>
                                    <td>{{ calculateBalance(index) }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button
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
                                            ></DeleteModal>
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
                                        v-if="form.process_type == 'Expanse'"
                                    >
                                        <label>{{ "Expanse Type" }} </label>
                                        <Multiselect
                                            v-model="form.expanse_type"
                                            :options="ExpansTypesOpions"
                                            :searchable="true"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.expanse_type,
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.expanse_type"
                                        >
                                            {{ formErrors.expanse_type[0] }}
                                        </div>
                                    </div>

                                    <div
                                        class="col-md-6 col-12"
                                        v-if="form.process_type == 'Expanse'"
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
                                            >Remarks</label
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
                                            >Ref no</label
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
                expanse_type: "",
                income_type: "",
                process_type: "",
            },
            formErrors: [],
            formStatus: 1, // 1 = ready, 0 = saving
            isCashInReadonly: false,
            isCashOutReadonly: false,
            ExpansTypesOpions: [],
            IncomeTypesOpions: [],
            methodTypesOpions: ["Bank", "Cash"],
            processTypeOptions: ["Expanse", "Income"],
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
                2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024,
                2025, 2026, 2027, 2028, 2029, 2030, 2031, 2032, 2033, 2034,
                2035,
            ],
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

            if (filter === "Monthly" && this.selectedMonth) {
                this.filteredEntries = this.transactionEntries.filter(
                    (entry) => {
                        return (
                            new Date(entry.transaction_date).getMonth() + 1 ===
                            parseInt(this.selectedMonth)
                        );
                    }
                );
            } else if (filter === "Yearly" && this.selectedYear) {
                this.filteredEntries = this.transactionEntries.filter(
                    (entry) => {
                        return (
                            new Date(entry.transaction_date).getFullYear() ===
                            parseInt(this.selectedYear)
                        );
                    }
                );
            } else if (filter === "Custom" && this.startDate && this.endDate) {
                const start = new Date(this.startDate);
                const end = new Date(this.endDate);
                this.filteredEntries = this.transactionEntries.filter(
                    (entry) => {
                        const date = new Date(entry.transaction_date);
                        return date >= start && date <= end;
                    }
                );
            } else {
                this.filteredEntries = this.transactionEntries; // Reset to all
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
        // calculateBalance(index) {
        //     let balance = 0;

        //     // Iterate through transactions up to the current index to calculate the running balance
        //     for (let i = 0; i <= index; i++) {
        //         const entry = this.transactionEntries[i];
        //         balance += parseFloat(entry.cash_in || 0);  // Add cash_in
        //         balance -= parseFloat(entry.cash_out || 0); // Subtract cash_out
        //     }

        //     return balance.toFixed(2);  // Format balance to 2 decimal places
        // },
        submit() {
            this.formStatus = 1;
            axios
                .post(route("api.transaction.store"), this.form)
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
                    if (error.response.data.errors) {
                    }
                });
        },
        // Calculate running balance dynamically based on the previous entries

        formatCurrency(value) {
            return new Intl.NumberFormat("en-PK", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
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
                .get(route("api.expanse.pluck"))
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
                this.transactionEntries.map((entry) => ({
                    Date: entry.transaction_date,
                    Remarks: entry.remarks,
                    Method: entry.method,
                    Type: entry.expanse_type ?? entry.income_type,
                    "Cash In": entry.cash_in ?? 0,
                    "Cash Out": entry.cash_out ?? 0,
                    Balance: this.calculateBalance(
                        this.transactionEntries.indexOf(entry)
                    ),
                }))
            );
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Transactions");
            XLSX.writeFile(wb, "transactions.xlsx");
        },

        // Export data to PDF
        exportToPDF() {
            const doc = new jsPDF();

            // Add title to the PDF
            doc.setFontSize(18); // Set the font size for the title
            doc.text("All Transactions List", 14, 20); // Add the title at coordinates (14, 20)

            // Create the table content
            const rows = this.transactionEntries.map((entry) => [
                entry.transaction_date,
                entry.remarks,
                entry.method,
                entry.expanse_type ?? entry.income_type,
                entry.cash_in ?? 0,
                entry.cash_out ?? 0,
                this.calculateBalance(this.transactionEntries.indexOf(entry)),
            ]);

            // Add table below the title
            doc.autoTable({
                head: [
                    [
                        "Date",
                        "Remarks",
                        "Method",
                        "Type",
                        "Cash In",
                        "Cash Out",
                        "Balance",
                    ],
                ],
                body: rows,
                startY: 30, // Set starting Y position for the table (below the title)
            });

            // Save the PDF
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
.multiselect
{
    width: 200px !important;
    
}
</style>
