<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1 class="theme-text-color">Trips</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/dashboard">R & R Coal</a>
                        </li>
                        <li class="breadcrumb-item">Trips</li>
                        <li class="breadcrumb-item active">Index</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button
                    class="btn btn-success mt-3"
                    data-bs-toggle="modal"
                    data-bs-target="#tripmodal"
                    @click="clearFields"
                >
                    <i class="bi bi-plus-lg"></i> Add New Trip
                </button>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title theme-text-color">All Trips</h5>
                    <div class="table-responsive">
                        <!-- Table with stripped rows -->
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Customer</th>
                                    <th scope="col">Supplier</th>
                                    <th scope="col">Mine</th>
                                    <th scope="col">Mine expense</th>
                                    <th scope="col">Vehicle</th>
                                    <th scope="col">Weight</th>
                                    <th scope="col">Truck Price</th>
                                    <th scope="col">Price per ton</th>
                                    <th scope="col">Transport</th>
                                    <th scope="col">Tax</th>
                                    <th scope="col">Date</th>

                                    <th scope="col">Total Bill</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(trip, index) in Trips"
                                    :key="trip.id"
                                >
                                    <th scope="row">{{ index + 1 }}</th>
                                    <th scope="row">
                                        <a
                                            class="theme-text-color"
                                            :href="
                                                route(
                                                    'customer.details',
                                                    trip.customer_id
                                                )
                                            "
                                        >
                                            {{ trip.customer_name }}</a
                                        >
                                    </th>
                                    <th scope="row">
                                        <a
                                            class="theme-text-color"
                                            :href="
                                                route(
                                                    'supplier.details',
                                                    trip.supplier_id
                                                )
                                            "
                                            >{{ trip.supplier_name }}</a
                                        >
                                    </th>
                                    <td>
                                        {{ trip.mine_name }}
                                    </td>

                                    <td>{{ trip.mine_expense }}</td>

                                    <td>
                                        {{ trip.vehicle }}
                                    </td>
                                    <td>
                                        {{ trip.truck_weight }}
                                    </td>
                                    <td>{{ trip.truck_price }}</td>
                                    <td>{{ trip.price_per_ton }}</td>
                                    <td>{{ trip.transport }}</td>
                                    <td>{{ trip.tax }}</td>
                                    <td>{{ trip.date }}</td>

                                    <td>{{ trip.total_bill }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <Link
                                                type="button"
                                                class="btn btn-sm fs-6"
                                                :title="'Edit'"
                                                data-bs-toggle="modal"
                                                data-bs-target="#tripmodal"
                                                @click="
                                                    showTrip(trip.id),
                                                        clearFields()
                                                "
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </Link>
                                            <DeleteModal
                                                :deleteId="trip.id"
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

            <!-- Trip modal -->
            <div
                class="modal fade"
                id="tripmodal"
                tabindex="-1"
                aria-labelledby="exampleModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="section-title mt-1">
                                <h5 class="c-theme-red">Trip</h5>
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
                                    <!-- <div class="col-12 col-md-12">
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
                                    </div> -->
                                    <div class="col-12 col-md-6">
                                        <label>{{ "Order" }}</label>
                                        <Multiselect
                                            v-model="form.order_id"
                                            :options="orders"
                                            :placeholder="'Order'"
                                            :searchable="true"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.order_id,
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.order_id"
                                        >
                                            {{ formErrors.order_id[0] }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label>{{ "Supplier" }}</label>
                                        <Multiselect
                                            v-model="form.supplier_id"
                                            :options="suppliers"
                                            :placeholder="'Customer'"
                                            :searchable="true"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.supplier_id,
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.supplier_id"
                                        >
                                            {{ formErrors.supplier_id[0] }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Mine Name</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-model="form.mine_name"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.mine_name,
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.mine_name"
                                        >
                                            {{ formErrors.mine_name[0] }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Mine Expense</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-model="form.mine_expense"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.mine_expense,
                                            }"
                                            @input="
                                                calculate(form.mine_expense)
                                            "
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.mine_expense"
                                        >
                                            {{ formErrors.mine_expense[0] }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Price / Ton</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-model="form.price_per_ton"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.price_per_ton,
                                            }"
                                            @input="
                                                calculate(form.price_per_ton)
                                            "
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.price_per_ton"
                                        >
                                            {{ formErrors.price_per_ton[0] }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Truck Weight (Ton)</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-model="form.truck_weight"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.truck_weight,
                                            }"
                                            @input="
                                                calculate(form.truck_weight)
                                            "
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.truck_weight"
                                        >
                                            {{ formErrors.truck_weight[0] }}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Truck Price</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-model="form.truck_price"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.truck_price,
                                            }"
                                            readonly
                                            @input="calculate(form.truck_price)"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.truck_price"
                                        >
                                            {{ formErrors.truck_price[0] }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Vehicle #</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-model="form.vehicle"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.vehicle,
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.vehicle"
                                        >
                                            {{ formErrors.vehicle[0] }}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Transport Charges</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-model="form.transport"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.transport,
                                            }"
                                            @input="calculate(form.transport)"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.transport"
                                        >
                                            {{ formErrors.transport[0] }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Tax's</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-model="form.tax"
                                            :class="{
                                                'invalid-bg': formErrors.tax,
                                            }"
                                            @input="calculate(form.tax)"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.tax"
                                        >
                                            {{ formErrors.tax[0] }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Date</label>
                                        <Datepicker
                                            v-model="form.date"
                                            autoApply
                                            :enableTimePicker="false"
                                            :class="{
                                                'invalid-bg': formErrors.date,
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.date"
                                        >
                                            {{ formErrors.date[0] }}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Total Bill</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            readonly
                                            v-model="form.total_bill"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.total_bill,
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.total_bill"
                                        >
                                            {{ formErrors.total_bill[0] }}
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
                    hidden="hidden"
                    data-bs-toggle="modal"
                    data-bs-target="#tripmodal"
                    title="Delete"
                    ref="closeModal"
                ></button>
            </div>
        </section>
    </main>
</template>
<script>
import Master from "../Layout/Master.vue";
import axios from "axios";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import Multiselect from "@vueform/multiselect";

export default {
    layout: Master,
    components: {
        Datepicker,
        Multiselect,
    },
    data() {
        return {
            Trips: [],
            form: {
                order_id: "",
                supplier_id: "",
                mine_name: "",
                truck_weight: "",
                price_per_ton: "",
                truck_price: "",
                vehicle: "",
                mine_expense: "",
                transport: "",
                tax: "",
                date: "",
                status: false,
                total_bill: "",
            },
            formErrors: [],
            suppliers: [],
            orders: [],
            formStatus: 1,
        };
    },
    created() {
        this.fetchTrips();
        this.suppliersPluck();
        this.ordersPluck();
    },
    methods: {
        calculate() {
            // Reset total bill and sum up all relevant fields
            this.form.total_bill =
                parseFloat(this.form.mine_expense || 0) +
                parseFloat(this.form.price_per_ton || 0) +
                parseFloat(this.form.truck_price || 0) +
                parseFloat(this.form.transport || 0) +
                parseFloat(this.form.tax || 0);

            this.form.truck_price =
                parseFloat(this.form.price_per_ton || 0) *
                this.form.truck_weight;
        },
        suppliersPluck() {
            axios
                .get(route("api.suppliers.pluck"))
                .then((response) => {
                    this.suppliers = response.data;
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        ordersPluck() {
            axios
                .get(route("api.orders.pluck"))
                .then((response) => {
                    this.orders = response.data;
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        fetchTrips() {
            axios
                .get(route("api.trip.fetch"))
                .then((response) => {
                    this.Trips = response.data;
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        submit() {
            this.formStatus = 0;
            let formData = new FormData();
            for (const key in this.form) {
                formData.append(key, this.form[key] ?? "");
            }
            axios
                .post(route("api.trip.store"), formData)
                .then(() => {
                    this.formStatus = 1;
                    toastr.success("Trip saved successfully");
                    this.fetchTrips();
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
                order_id: "",
                supplier_id: "",
                mine_name: "",
                truck_weight: "",
                price_per_ton: "",
                truck_price: "",
                vehicle: "",
                mine_expense: "",
                transport: "",
                tax: "",
                date: "",
                status: false,
                total_bill: "",
            };
            this.formErrors = [];
            this.formStatus = 1;
        },
        showTrip(trip_id) {
            axios
                .get(route("api.trip.show", trip_id))
                .then((response) => {
                    this.form = response.data;
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        deleteThis(trip_id) {
            axios
                .delete(route("api.trip.delete", trip_id))
                .then(() => {
                    toastr.success("Trip deleted successfully");
                    this.fetchTrips();
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
    },
};
</script>
