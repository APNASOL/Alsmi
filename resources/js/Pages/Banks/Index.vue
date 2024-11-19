<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1 class="theme-text-color">Banks</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/dashboard">R & R Coal</a>
                        </li>
                        <li class="breadcrumb-item">Banks</li>
                        <li class="breadcrumb-item active">Index</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button
                    class="btn btn-success mt-3"
                    data-bs-toggle="modal"
                    data-bs-target="#bankmodal"
                    @click="clearFields"
                >
                    <i class="bi bi-plus-lg"></i> Add New Bank
                </button>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title theme-text-color">All Banks</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Bank Name</th>
                                    <th scope="col">Branch</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Account #</th>
                                    <th scope="col">Current Amount</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(bank, index) in Banks"
                                    :key="bank.id"
                                >
                                    <th scope="row">{{ index + 1 }}</th>
                                    <td>{{ bank.name }}</td>
                                    <td>{{ bank.branch }}</td>
                                    <td>{{ bank.title }}</td>
                                    <td>{{ bank.account }}</td>
                                    <td>{{ bank.current_amount }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button
                                                class="btn btn-sm fs-6"
                                                title="Edit"
                                                data-bs-toggle="modal"
                                                data-bs-target="#bankmodal"
                                                @click="
                                                    showBank(bank.id);
                                                    clearFields();
                                                "
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <DeleteModal
                                                :deleteId="bank.id"
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

            <!-- Bank Modal -->
            <div
                class="modal fade"
                id="bankmodal"
                tabindex="-1"
                aria-labelledby="exampleModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-primary" v-if="form.id">
                                {{ form.name }} - {{ form.title }}
                                <small>({{ form.account }})</small>
                            </h5>
                            <h5 class="modal-title text-primary" v-else>
                                New Bank
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
                                        <label for="name" class="form-label"
                                            >Bank Name</label
                                        >
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="name"
                                            v-model="form.name"
                                            :class="{
                                                'invalid-bg': formErrors.name,
                                            }"
                                        />
                                        <div
                                            v-if="formErrors.name"
                                            class="invalid-feedback"
                                        >
                                            {{ formErrors.name[0] }}
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label for="branch" class="form-label"
                                            >Branch</label
                                        >
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="branch"
                                            v-model="form.branch"
                                            :class="{
                                                'invalid-bg': formErrors.branch,
                                            }"
                                        />
                                        <div
                                            v-if="formErrors.branch"
                                            class="invalid-feedback"
                                        >
                                            {{ formErrors.branch[0] }}
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <label for="title" class="form-label"
                                            >Title</label
                                        >
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="title"
                                            v-model="form.title"
                                            :class="{
                                                'invalid-bg': formErrors.title,
                                            }"
                                        />
                                        <div
                                            v-if="formErrors.title"
                                            class="invalid-feedback"
                                        >
                                            {{ formErrors.title[0] }}
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
                                                'invalid-bg':
                                                    formErrors.account,
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
            Banks: [],
            form: {
                id: "",
                name: "",
                branch: "",
                date: "",
                title: "",
                account: "",
                current_amount: "",
            },
            formErrors: [],
            formStatus: 1, // 1 = ready, 0 = saving
        };
    },
    created() {
        this.fetchBanks();
    },
    methods: {
        fetchBanks() {
            axios
                .get(route("api.bank.fetch"), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.Banks = response.data;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        submit() {
            this.formStatus = 0;
            axios
                .post(route("api.bank.store"), this.form)
                .then(() => {
                    this.formStatus = 1;
                    this.fetchBanks();

                    toastr.success("Bank record saved successfully.");
                    this.$refs.closeModal?.click();
                })
                .catch((error) => {
                    this.formStatus = 1;
                    this.formErrors = error.response.data.errors || {};
                });
        },
        clearFields() {
            this.form = {
                id: "",
                name: "",
                branch: "",
                date: "",
                title: "",
                account: "",
                current_amount: "",
            };
            this.formErrors = [];
        },
        showBank(bank_id) {
            axios
                .get(route("api.bank.show", bank_id))
                .then((response) => {
                    this.form = { ...response.data };
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        deleteThis(id) {
            axios
                .delete(route("api.bank.delete", id))
                .then(() => {
                    this.fetchBanks();
                    toastr.success("Bank record deleted successfully.");
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
