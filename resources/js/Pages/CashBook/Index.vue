<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1 class="theme-text-color">Cashbook</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/dashboard">R & R Coal</a>
                        </li>
                        <li class="breadcrumb-item">Cashbook</li>
                        <li class="breadcrumb-item active">Index</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button
                    class="btn btn-success mt-3"
                    data-bs-toggle="modal"
                    data-bs-target="#cashbookmodal"
                    @click="clearFields"
                >
                    <i class="bi bi-plus-lg"></i> Add New Entry
                </button>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title theme-text-color">
                        All Cashbook Entries
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Notes</th>
                                    <th scope="col">Debit (Cash In)</th>
                                    <th scope="col">Credit (Cash Out)</th>
                                    <th scope="col">Balance</th>
                                    <th scope="col">Date</th>
                              
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(entry, index) in cashbookEntries"
                                    :key="entry.id"
                                >
                                    <th scope="row">{{ index + 1 }}</th>
                                    <td>{{ entry.notes }}</td>
                                    <td>{{ entry.cash_in }}</td>
                                    <td>{{ entry.cash_out }}</td>
                                    <td>{{ calculateBalance(index) }}</td>
                                    <!-- Dynamically calculated balance -->
                                    <td>{{ entry.date }}</td>
                                     
                                    <td>
                                        <div class="btn-group">
                                            <button
                                                class="btn btn-sm fs-6"
                                                title="Edit"
                                                data-bs-toggle="modal"
                                                data-bs-target="#cashbookmodal"
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

            <!-- Cashbook Modal -->
            <div
                class="modal fade"
                id="cashbookmodal"
                tabindex="-1"
                aria-labelledby="exampleModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-primary" v-if="form.id">
                                {{ form.notes }} - {{ form.type }}
                                <small>({{ form.date }})</small>
                            </h5>
                            <h5 class="modal-title text-primary" v-else>
                                New Cashbook Entry
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
                                        <label for="notes" class="form-label"
                                            >Notes</label
                                        >
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="notes"
                                            v-model="form.notes"
                                            :class="{
                                                'invalid-bg': formErrors.notes,
                                            }"
                                        />
                                        <div
                                            v-if="formErrors.notes"
                                            class="invalid-feedback"
                                        >
                                            {{ formErrors.notes[0] }}
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <label for="type" class="form-label"
                                            >Type</label
                                        >
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="type"
                                            v-model="form.type"
                                            :class="{
                                                'invalid-bg': formErrors.type,
                                            }"
                                        />
                                        <div
                                            v-if="formErrors.type"
                                            class="invalid-feedback"
                                        >
                                            {{ formErrors.type[0] }}
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
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

                                    <div class="col-md-6 col-12">
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

export default {
    layout: Master,
    data() {
        return {
            cashbookEntries: [],
            form: {
                id: "",
                notes: "",
                type: "",
                cash_in: "",
                cash_out: "",

                date: "",
                
            },
            formErrors: [],
            formStatus: 1, // 1 = ready, 0 = saving
        };
    },
    created() {
        this.fetchCashbookEntries();
    },
    methods: {
        fetchCashbookEntries() {
            axios
                .get(route("api.cashbook.fetch"))
                .then((response) => {
                    this.cashbookEntries = response.data;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        submit() {
            this.formStatus = 0;
            axios
                .post(route("api.cashbook.store"), this.form)
                .then(() => {
                    this.formStatus = 1;
                    this.fetchCashbookEntries();
                    toastr.success("Cashbook entry saved successfully.");
                    this.$refs.closeModal?.click();
                })
                .catch((error) => {
                    this.formStatus = 1;
                    this.formErrors = error.response.data.errors || {};
                });
        },
        // Calculate running balance dynamically based on the previous entries
        
        calculateBalance(index) {
    let balance = 0;
    for (let i = 0; i <= index; i++) {
        const entry = this.cashbookEntries[i];
        const cashIn = parseFloat(entry.cash_in) || 0;  // Ensure it's a number
        const cashOut = parseFloat(entry.cash_out) || 0; // Ensure it's a number
        balance += cashIn;
        balance -= cashOut;
    }
    return this.formatCurrency(balance);
},


        formatCurrency(value) {
            return new Intl.NumberFormat("en-PK", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(value);
        },
        

        clearFields() {
            this.form = {
                id: "",
                notes: "",
                type: "",
                cash_in: "",
                cash_out: "",

                date: "",
        
            };
            this.formErrors = [];
        },
        showEntry(entry_id) {
            axios
                .get(route("api.cashbook.show", entry_id))
                .then((response) => {
                    this.form = { ...response.data };
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        deleteThis(id) {
            axios
                .delete(route("api.cashbook.delete", id))
                .then(() => {
                    this.fetchCashbookEntries();
                    toastr.success("Cashbook entry deleted successfully.");
                })
                .catch((error) => {
                    console.error(error);
                });
        },
    },
};
</script>

<style scoped>
.invalid-bg {
    border-color: #f8d4d4;
    background-color: #f8d4d4;
}
.invalid-feedback {
    color: red;
    font-size: 0.875rem;
}
</style>
