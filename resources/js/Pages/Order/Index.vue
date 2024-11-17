<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1 class="theme-text-color">Orders</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/dashboard">R & R Coal</a>
                        </li>
                        <li class="breadcrumb-item">Orders</li>
                        <li class="breadcrumb-item active">Index</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button
                    class="btn btn-success mt-3"
                    data-bs-toggle="modal"
                    data-bs-target="#ordermodal"
                    @click="clearFields"
                >
                    <i class="bi bi-plus-lg"></i> Add New Order
                </button>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title theme-text-color">All Orders</h5>
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
                                    v-for="(order, index) in Orders"
                                    :key="order.id"
                                >
                                    <th scope="row">{{ index + 1 }}</th>
                                    <td>
                                        <a
                                            class="theme-text-color"
                                            :href="
                                                route('order.details', order.id)
                                            "
                                        >
                                            {{ order.customer_name }}
                                        </a>
                                    </td>
                                    <td>{{ order.start_date }}</td>
                                    <td>{{ order.end_date }}</td>
                                    <td>{{ order.status }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <Link
                                                type="button"
                                                class="btn btn-sm fs-6"
                                                :title="'Edit'"
                                                data-bs-toggle="modal"
                                                data-bs-target="#ordermodal"
                                                @click="
                                                    showOrder(order.id),
                                                        clearFields()
                                                "
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </Link>
                                            <DeleteModal
                                                :deleteId="order.id"
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

            <!-- Order modal -->
            <div
                class="modal fade"
                id="ordermodal"
                tabindex="-1"
                aria-labelledby="exampleModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="section-title mt-1">
                                <h5 class="c-theme-red">Order</h5>
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

                                    <div class="col-12 col-md-12">
                                        <label>{{ "Customer" }}</label>
                                        <Multiselect
                                            v-model="form.customer"
                                            :options="pluckedCustomers"
                                            :placeholder="'Customer'"
                                            :searchable="true"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.customer,
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.customer"
                                        >
                                            {{ formErrors.customer[0] }}
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label>
                                            {{ "Start Date" }}
                                        </label>
                                        <Datepicker
                                            autoApply
                                            :enableTimePicker="false"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.start_date,
                                            }"
                                            v-model="form.start_date"
                                        >
                                        </Datepicker>
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.start_date"
                                        >
                                            {{ formErrors.start_date[0] }}
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label>
                                            {{ "End Date" }}
                                        </label>
                                        <Datepicker
                                            autoApply
                                            :enableTimePicker="false"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.end_date,
                                            }"
                                            v-model="form.end_date"
                                        >
                                        </Datepicker>
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.end_date"
                                        >
                                            {{ formErrors.end_date[0] }}
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
                    data-bs-target="#ordermodal"
                    ref="closeModal"
                ></button>
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

export default {
    layout: Master,
    components: {
        Multiselect,
        Datepicker,
    },
    data() {
        return {
            Orders: [],
            form: {
                id: "",
                customer: "",
                start_date: "",
                end_date: "",
                status: "",
            },
            formErrors: [],
            formStatus: 1,
            pluckedCustomers: [],
        };
    },
    created() {
        this.fetchCustomers();
        this.pluckCustomers();
    },
    methods: {
        fetchCustomers() {
            axios
                .get(route("api.order.fetch"))
                .then((response) => {
                    this.Orders = response.data;
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
                "customer",
                this.form.customer == "" || this.form.customer == null
                    ? ""
                    : this.form.customer
            );

            if (this.form.start_date) {
                const startDateObject = new Date(this.form.start_date);
                let start_date = startDateObject.toLocaleString("en-US");
                formData.append("start_date", start_date ?? "");
            }
            if (this.form.end_date) {
                const startDateObject = new Date(this.form.end_date);
                let end_date = startDateObject.toLocaleString("en-US");
                formData.append("end_date", end_date ?? "");
            }

            formData.append(
                "status",
                this.form.status === "" || this.form.status === null
                    ? ""
                    : this.form.status
                    ? "true"
                    : "false"
            );

            axios
                .post(route("api.order.store"), formData)
                .then(() => {
                    this.formStatus = 1;
                    toastr.success("Order saved successfully");
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
                status: false,
            };
            this.formErrors = [];
            this.formStatus = 1;
        },
        showOrder(order_id) {
            axios
                .get(route("api.order.show", order_id))
                .then((response) => {
                    this.form.id = response.data.id;
                    this.form.customer = response.data.customer_id;
                    this.form.status = response.data.status;
                    this.form.start_date = response.data.start_date;
                    this.form.end_date = response.data.end_date;
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        pluckCustomers() {
            axios
                .get(route("api.customers.pluck"))
                .then((response) => {
                    this.pluckedCustomers = response.data;
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        deleteThis(id) {
            axios
                .delete(route("api.order.delete", id))
                .then(() => {
                    toastr.success("Order deleted successfully");
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
