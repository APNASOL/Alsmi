<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1 class="theme-text-color">Supplier</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/dashboard">R & R Coal</a>
                        </li>
                        <li class="breadcrumb-item">Supplier</li>
                        <li class="breadcrumb-item active">Index</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button
                    class="btn btn-success mt-3"
                    data-bs-toggle="modal"
                    data-bs-target="#suppliermodal"
                    @click="clearFields"
                >
                    <i class="bi bi-plus-lg"></i> Add New Supplier
                </button>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title theme-text-color">All Supplier</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">Old amount</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(supplier, index) in Supplier"
                                    :key="supplier.id"
                                >
                                    <th scope="row">{{ index + 1 }}</th>
                                    <td>
                                        <a
                                            class="theme-text-color"
                                            :href="
                                                route(
                                                    'supplier.details',
                                                    supplier.id
                                                )
                                            "
                                        >
                                            {{ supplier.name }}</a
                                        >
                                    </td>
                                    <td>{{ supplier.contact }}</td>
                                    <td>{{ supplier.address }}</td>
                                    <td>{{ supplier.old_amount }}</td>
                                    <td>
                                        {{ supplier.status }}
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <Link
                                                type="button"
                                                class="btn btn-sm fs-6"
                                                :title="'Edit'"
                                                data-bs-toggle="modal"
                                                data-bs-target="#suppliermodal"
                                                @click="
                                                    showSupplier(supplier.id),
                                                        clearFields()
                                                "
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </Link>
                                            <DeleteModal
                                                :deleteId="supplier.id"
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

            <!-- Supplier Modal -->
            <div
                class="modal fade"
                id="suppliermodal"
                tabindex="-1"
                aria-labelledby="exampleModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="section-title mt-1">
                                <h5 class="c-theme-red">Supplier</h5>
                            </div>
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
                                    <div class="col-12 col-md-12">
                                        <label>Status</label>
                                        <br />
                                        <div class="d-inline-block me-2">
                                            Inactive
                                        </div>
                                        <div
                                            class="form-check form-switch d-inline-block"
                                        >
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                v-model="form.status"
                                            />
                                            <label class="form-check-label"
                                                >Active</label
                                            >
                                        </div>
                                        <div
                                            v-if="formErrors.status"
                                            class="invalid-feedback"
                                        >
                                            {{ formErrors.status[0] }}
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label">Name</label>
                                        <input
                                            type="name"
                                            class="form-control"
                                            v-model="form.name"
                                            :class="{
                                                'invalid-bg': formErrors.name,
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.name"
                                        >
                                            {{ formErrors.name[0] }}
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label class="form-label"
                                            >Contact</label
                                        >
                                        <input
                                            type="number"
                                            class="form-control"
                                            v-model="form.contact"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.contact,
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.contact"
                                        >
                                            {{ formErrors.contact[0] }}
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label for="contact" class="form-label"
                                            >Old Amount</label
                                        >
                                        <input
                                            type="number"
                                            class="form-control"
                                            id="contact"
                                            v-model="form.old_amount"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.old_amount,
                                            }"
                                        />
                                        <div
                                            v-if="formErrors.old_amount"
                                            class="invalid-feedback"
                                        >
                                            {{ formErrors.old_amount[0] }}
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label"
                                            >Address</label
                                        >
                                        <textarea
                                            type="address"
                                            class="form-control"
                                            v-model="form.address"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.address,
                                            }"
                                        ></textarea>
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.address"
                                        >
                                            {{ formErrors.address[0] }}
                                        </div>
                                    </div>

                                    <div class="mt-5">
                                        <button
                                            type="submit"
                                            class="btn btn-success"
                                            v-if="formStatus == 1"
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
                                                role="status"
                                                aria-hidden="true"
                                            ></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button
                    hidden="hidden"
                    data-bs-toggle="modal"
                    data-bs-target="#suppliermodal"
                    ref="closeModal"
                ></button>
            </div>
        </section>
    </main>
</template>

<script>
import Master from "../Layout/Master.vue";
import axios from "axios";

export default {
    layout: Master,
    data() {
        return {
            Supplier: [],
            form: {
                id: "",
                name: "",
                contact: "",
                address: "",
                old_amount: "",
                status: "",
            },
            formErrors: [],
            formStatus: 1,
            supplied_id: "",
        };
    },
    methods: {
        fetchSupplier() {
            axios
                .get(route("api.supplier.fetch"), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.Supplier = response.data;
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        submit() {
            this.formStatus = 0;
            let formData = new FormData();
            formData.append("id", this.form.id);
            formData.append("name", this.form.name || "");
            formData.append("contact", this.form.contact || "");
            formData.append("old_amount", this.form.old_amount || "");
            formData.append("address", this.form.address || "");

            formData.append(
                "status",
                this.form.status === "" || this.form.status === null
                    ? ""
                    : this.form.status
                    ? "true"
                    : "false"
            );

            axios
                .post(route("api.supplier.store"), formData, {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.formStatus = 1;
                    toastr.success("Supplier record saved successfully");
                    this.fetchSupplier();
                    this.$refs.closeModal.click();
                    this.supplied_id = "";
                })
                .catch((error) => {
                    this.formStatus = 1;
                    toastr.error(error.response.data.message);
                    this.formErrors = error.response.data.errors;
                });
        },
        clearFields() {
            this.formStatus = 1;
            this.form.name = "";
            this.form.contact = "";
            this.form.address = "";
            this.form.old_amount = "";
            this.form.status = "";
            this.formErrors = [];
        },
        showSupplier(supplier_id) {
            axios
                .get(route("api.supplier.show", supplier_id), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.form.id = response.data.id;
                    this.form.name = response.data.name;
                    this.form.address = response.data.address;
                    this.form.contact = response.data.contact;
                    this.form.old_amount = response.data.old_amount;
                    this.form.status = response.data.status;
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        deleteThis(id) {
            axios
                .delete(route("api.supplier.delete", id), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then(() => {
                    toastr.success("Supplier deleted successfully.");
                    this.fetchSupplier();
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
    },

    created() {
        this.fetchSupplier();
    },
};
</script>

<style lang="scss">
@import "@vueform/multiselect/themes/default.css";
.invalid-feedback {
    display: block !important;
}
.invalid-bg {
    border-color: #f8d4d4 !important;
    background-color: #f8d4d4 !important;
}
</style>
