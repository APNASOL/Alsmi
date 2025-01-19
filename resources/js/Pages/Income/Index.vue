<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1 class="theme-text-color">Income Types</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/dashboard">Alsmi</a>
                        </li>
                        <li class="breadcrumb-item">Income Types</li>
                        <li class="breadcrumb-item active">Index</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button
                    class="btn btn-success mt-3"
                    data-bs-toggle="modal"
                    data-bs-target="#updateRecordModal"
                    @click="clearFields"
                >
                    <i class="bi bi-plus-lg"></i> New Income Types
                </button>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title theme-text-color">
                        All Income Types
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>

                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(income, index) in IncomeTYpes"
                                    :key="income.id"
                                >
                                    <th scope="row">{{ index + 1 }}</th>
                                    <td>{{ income.name }}</td>

                                    <td>
                                        <div class="btn-group">
                                            <button
                                                class="btn btn-sm fs-6"
                                                title="Edit"
                                                data-bs-toggle="modal"
                                                data-bs-target="#updateRecordModal"
                                                @click="
                                                    showEntry(income.id);
                                                    clearFields();
                                                "
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <DeleteModal
                                                :deleteId="income.id"
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
                id="updateRecordModal"
                tabindex="-1"
                aria-labelledby="exampleModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-primary" v-if="form.id">
                                {{ form.name }}
                            </h5>
                            <h5 class="modal-title text-primary" v-else>
                                New {{ process }} Entry
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
                                    <div class="col-12">
                                        <label for="name" class="form-label"
                                            >{{ process }} name</label
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
            IncomeTYpes: [],
            form: {
                id: "",
                name: "",
                process: this.process,
            },
            formErrors: [],
            formStatus: 1, // 1 = ready, 0 = saving
            process: "Income",
        };
    },
    created() {
        this.fetchIncomes();
    },
    methods: {
        fetchIncomes() {
            axios
                .get(route("api.income.expanse.fetch", this.process))
                .then((response) => {
                    this.IncomeTYpes = response.data;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        showEntry(entry_id) {
            axios
                .get(route("api.income.expanse.show", [entry_id, this.process]))
                .then((response) => {
                    this.form.id = response.data.id;
                    this.form.name = response.data.name;
                })
                .catch((error) => {
                    toastr.error(error);
                });
        },
        submit() {
            this.formStatus = 0;

            // Create new income type
            axios
                .post(route("api.income.expanse.store"), this.form)
                .then(() => {
                    this.formStatus = 1;
                    toastr.success("Income Type created successfully.");
                    this.fetchIncomes();
                    this.$refs.closeModal.click();
                })
                .catch((error) => {
                    this.formStatus = 1;
                    this.formErrors = error.response.data.errors || {};

                    toastr.error(error.response.data.message);
                });
        },
        deleteThis(id) {
            axios
                .delete(route("api.income.expanse.delete", [id, this.process]))
                .then(() => {
                    this.fetchIncomes();
                    toastr.success("Income Type deleted successfully.");
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        clearFields() {
            this.form.id = "";
            this.form.name = "";
            this.formErrors = [];
        },
    },
};
</script>
