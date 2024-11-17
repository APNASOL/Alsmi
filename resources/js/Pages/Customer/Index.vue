<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1 class="theme-text-color">Customers</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/dashboard">R & R Coal</a>
                        </li>
                        <li class="breadcrumb-item">Customers</li>
                        <li class="breadcrumb-item active">Index</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button
                    class="btn btn-success mt-3"
                    data-bs-toggle="modal"
                    data-bs-target="#customermodal"
                    @click="clearFields"
                >
                    <i class="bi bi-plus-lg"></i> Add New Customer
                </button>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title theme-text-color">All Customers</h5>
                    <div class="table-responsive">
                        <!-- Table with stripped rows -->
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col">Address</th>
                                     
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(customer, index) in Customers"
                                    :key="customer.id"
                                >
                                    <th scope="row">{{ index + 1 }}</th>
                                    <td>
                                        <a
                                            class="theme-text-color"
                                            :href="
                                                route(
                                                    'customer.details',
                                                    customer.id
                                                )
                                            "
                                            >{{ customer.name }}
                                        </a>
                                    </td>
                                    <td>{{ customer.contact }}</td>
                                    <td>{{ customer.address }}</td>
                               
                                    <td>{{ customer.status }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <Link
                                                type="button"
                                                class="btn btn-sm fs-6"
                                                :title="'Edit'"
                                                data-bs-toggle="modal"
                                                data-bs-target="#customermodal"
                                                @click="
                                                    showCustomer(customer.id),
                                                        clearFields()
                                                "
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </Link>
                                            <DeleteModal
                                                :deleteId="customer.id"
                                                @deleteThis="deleteThis"
                                            ></DeleteModal>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>
            </div>

            <!-- Customer modal -->
            <div
                class="modal fade"
                id="customermodal"
                tabindex="-1"
                aria-labelledby="exampleModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="section-title mt-1">
                                <h5 class="c-theme-red">Customer</h5>
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

                                    <div class="col-md-6 col-12">
                                        <label for="name" class="form-label"
                                            >Name</label
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
                                        <label for="contact" class="form-label"
                                            >Contact</label
                                        >
                                        <input
                                            type="number"
                                            class="form-control"
                                            id="contact"
                                            v-model="form.contact"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.contact,
                                            }"
                                        />
                                        <div
                                            v-if="formErrors.contact"
                                            class="invalid-feedback"
                                        >
                                            {{ formErrors.contact[0] }}
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
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

                                    <div class="col-md-6 col-12">
                                        <label for="address" class="form-label"
                                            >Address</label
                                        >
                                        <textarea
                                            class="form-control"
                                            id="address"
                                            v-model="form.address"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.address,
                                            }"
                                            rows="1"
                                        ></textarea>
                                        <div
                                            v-if="formErrors.address"
                                            class="invalid-feedback"
                                        >
                                            {{ formErrors.address[0] }}
                                        </div>
                                    </div>

                                    <div class="mt-5">
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
import Master from "../Layout/Master.vue";
import axios from "axios";

export default {
    layout: Master,
    data() {
        return {
            Customers: [],
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
        };
    },
    created() {
        this.fetchCustomers();
    },
    methods: {
        fetchCustomers() {
            axios
                .get(route("api.customer.fetch"))
                .then((response) => {
                    this.Customers = response.data;
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        submit() {
            this.formStatus = 0;
            let formData = new FormData();
            formData.append("id", this.form.id);

            formData.append(
                "name",
                this.form.name == "" || this.form.name == null
                    ? ""
                    : this.form.name
            );

            formData.append(
                "contact",
                this.form.contact == "" || this.form.contact == null
                    ? ""
                    : this.form.contact
            );

            formData.append(
                "address",
                this.form.address == "" || this.form.address == null
                    ? ""
                    : this.form.address
            );
            formData.append(
                "old_amount",
                this.form.old_amount == "" || this.form.old_amount == null
                    ? ""
                    : this.form.old_amount
            );

            formData.append(
                "status",
                this.form.status === "" || this.form.status === null
                    ? ""
                    : this.form.status
                    ? "true"
                    : "false"
            );

            axios
                .post(route("api.customer.store"), formData)
                .then(() => {
                    this.formStatus = 1;
                    toastr.success("Customer saved successfully");
                    this.fetchCustomers();
                    this.$refs.closeModal.click();
                })
                .catch((error) => {
                    this.formStatus = 1;
                    toastr.error(error.response.data.message);
                    this.formErrors = error.response.data.errors;
                });
        },
        clearFields() {
            this.form = {
                id: "",
                name: "",
                contact: "",
                address: "",
                old_amount: "",
                status: false,
            };
            this.formErrors = [];
            this.formStatus = 1;
        },
        showCustomer(customer_id) {
            axios
                .get(route("api.customer.show", customer_id))
                .then((response) => {
                    this.form = { ...response.data };
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        deleteThis(id) {
            axios
                .delete(route("api.customer.delete", id))
                .then(() => {
                    toastr.success("Customer deleted successfully");
                    this.fetchCustomers();
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
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
</style>
