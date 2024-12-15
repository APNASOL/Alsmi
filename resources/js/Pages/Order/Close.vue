<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1 class="theme-text-color">
                    {{ customerDetails.name }} - Order Closing
                </h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/">R & R Coal</a>
                        </li>
                        <li class="breadcrumb-item">Order</li>
                        <li class="breadcrumb-item active">Closing</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a
                    class="btn btn-success mt-3"
                    :href="route('order.details', orderId)"
                >
                    Go Back
                </a>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-danger fw-bold mb-4">
                        Order Duration {{ formatDate(order.start_date) }} -
                        {{ formatDate(order.end_date) }}
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Card container -->
                            <div class="row justify-content-left">
                                <!-- Card 1: Mine Expense -->
                                <div class="col-md-6 col-12">
                                    <div
                                        class="card border-0 shadow-sm rounded-3 bg-light"
                                    >
                                        <div class="card-body">
                                            <h5 class="text-primary fw-bold">
                                                Mine Expense
                                            </h5>
                                            <p
                                                class="card-text fs-5 fw-bold text-dark"
                                            >
                                                {{ totalMineExpense }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 2: Tax -->
                                <div class="col-md-6 col-12">
                                    <div
                                        class="card border-0 shadow-sm rounded-3 bg-light"
                                    >
                                        <div class="card-body">
                                            <h5 class="text-primary fw-bold">
                                                Tax
                                            </h5>
                                            <p
                                                class="card-text fs-5 fw-bold text-dark"
                                            >
                                                {{ totalTax }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 3: Transport -->
                                <div class="col-md-6 col-12">
                                    <div
                                        class="card border-0 shadow-sm rounded-3 bg-light"
                                    >
                                        <div class="card-body">
                                            <h5 class="text-primary fw-bold">
                                                Transport
                                            </h5>
                                            <p
                                                class="card-text fs-5 fw-bold text-dark"
                                            >
                                                {{ totalTransport }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 4: Truck Weight -->
                                <div class="col-md-6 col-12">
                                    <div
                                        class="card border-0 shadow-sm rounded-3 bg-light"
                                    >
                                        <div class="card-body">
                                            <h5 class="text-primary fw-bold">
                                                Truck Weight
                                            </h5>
                                            <p
                                                class="card-text fs-5 fw-bold text-dark"
                                            >
                                                {{ totalTruckWeight }} Ton
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 5: Price Per Ton -->
                                <!-- <div class="col-md-6 col-12">
                                    <div
                                        class="card border-0 shadow-sm rounded-3 bg-light"
                                    >
                                        <div class="card-body">
                                            <h5 class="text-primary fw-bold">
                                                Price Per Ton
                                            </h5>
                                            <p
                                                class="card-text fs-5 fw-bold text-dark"
                                            >
                                                {{ totalPricePerTon }}
                                            </p>
                                        </div>
                                    </div>
                                </div> -->

                                <!-- Card 6: Truck Price -->
                                <div class="col-md-6 col-12">
                                    <div
                                        class="card border-0 shadow-sm rounded-3 bg-light"
                                    >
                                        <div class="card-body">
                                            <h5 class="text-primary fw-bold">
                                                Truck Price
                                            </h5>
                                            <p
                                                class="card-text fs-5 fw-bold text-dark"
                                            >
                                                {{
                                                    formatNumber(
                                                        totalTruckPrice
                                                    )
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 7: Total Bill -->
                                <div class="col-md-6 col-12">
                                    <div
                                        class="card border-0 shadow-sm rounded-3 bg-light"
                                    >
                                        <div class="card-body">
                                            <h5 class="text-primary fw-bold">
                                                Total Bill
                                            </h5>
                                            <p
                                                class="card-text fs-5 fw-bold text-dark"
                                            >
                                                {{ formatNumber(totalBill) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card 7: Profit -->
                                <div class="col-md-12 col-12">
                                    <div
                                        class="card border-0 shadow-sm rounded-3 bg-light"
                                    >
                                        <div class="card-body">
                                            <h5 class="text-primary fw-bold">
                                                Profit
                                            </h5>
                                            <p
                                                class="card-text fs-5 fw-bold text-dark"
                                            >
                                                <!-- {{ form.profit }} -->
                                                {{ formatNumber(form.profit) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Divider -->
                        <div class="col-md-1 d-flex justify-content-center">
                            <div style="border-left: 1px solid #ddd"></div>
                        </div>

                        <div
                            class="col-md-5 card border-0 shadow-sm rounded-3 bg-light"
                        >
                            <h5 class="text-primary fw-bold mt-3 mb-2">
                                Profit Calculation
                            </h5>

                            <div class="row g-3">
                                <!-- Total Field -->
                                <div class="col-12">
                                    <label for="total" class="form-label"
                                        >Total</label
                                    >
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="total"
                                        v-model="form.total"
                                        readonly
                                        :class="{
                                            'invalid-bg': formErrors.total,
                                        }"
                                    />
                                    <div
                                        class="invalid-feedback animated fadeIn"
                                        v-if="formErrors.total"
                                    >
                                        {{ formErrors.total[0] }}
                                    </div>
                                </div>

                                <!-- Commission Field -->
                                <div class="col-12">
                                    <label for="commission" class="form-label"
                                        >Commission</label
                                    >
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="commission"
                                        v-model="form.commission"
                                        @input="calculateProfit"
                                        :class="{
                                            'invalid-bg': formErrors.commission,
                                        }"
                                    />
                                    <div
                                        class="invalid-feedback animated fadeIn"
                                        v-if="formErrors.commission"
                                    >
                                        {{ formErrors.commission[0] }}
                                    </div>
                                </div>

                                <!-- Premium Field -->
                                <div class="col-12">
                                    <label for="premium" class="form-label"
                                        >Premium</label
                                    >
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="premium"
                                        v-model="form.premium"
                                        @input="calculateProfit"
                                        :class="{
                                            'invalid-bg': formErrors.premium,
                                        }"
                                    />
                                    <div
                                        class="invalid-feedback animated fadeIn"
                                        v-if="formErrors.premium"
                                    >
                                        {{ formErrors.premium[0] }}
                                    </div>
                                </div>

                                <!-- Profit Field -->
                                <div class="col-12">
                                    <label for="profit" class="form-label"
                                        >Profit</label
                                    >
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="profit"
                                        v-model="form.profit"
                                        readonly
                                    />
                                </div>
                            </div>

                            <!-- Confirmation Modal or Spinner based on form status -->

                            <div class="mt-3">
                                <template v-if="isLoading">
                                    <span
                                        class="text-danger spinner-border spinner-border-sm"
                                        style="height: 50px; width: 50px"
                                        role="status"
                                    ></span>
                                </template>
                                <template v-else>
                                    <ConfirmationModal
                                        :recordId="orderId"
                                        @processThis="processThis"
                                    ></ConfirmationModal>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</template>

<script>
import ConfirmationModal from "../Global/ConfirmationModal.vue";
import Master from "../Layout/Master.vue";
import axios from "axios";

export default {
    layout: Master,
    props: ["orderId"],
    data() {
        return {
            orderDetails: [],
            order: [],
            customerDetails: [],
            form: {
                order_id: 0,
                total: 0,
                commission: 0,
                premium: 0,
                profit: 0,
            },

            formErrors:[],
            isLoading: false,
        };
    },
    computed: {
        totalMineExpense() {
            return this.orderDetails.reduce(
                (total, trip) => total + (trip.mine_expense || 0),
                0
            );
        },
        totalTax() {
            return this.orderDetails.reduce(
                (total, trip) => total + (trip.tax || 0),
                0
            );
        },
        totalTransport() {
            return this.orderDetails.reduce(
                (total, trip) => total + (trip.transport || 0),
                0
            );
        },
        totalTruckWeight() {
            return this.orderDetails.reduce(
                (total, trip) => total + (trip.truck_weight || 0),
                0
            );
        },
        totalPricePerTon() {
            return this.orderDetails.reduce(
                (total, trip) => total + (trip.price_per_ton || 0),
                0
            );
        },
        totalTruckPrice() {
            return this.orderDetails.reduce(
                (total, trip) => total + (trip.truck_price || 0),
                0
            );
        },
        totalBill() {
            return this.orderDetails.reduce(
                (total, trip) => total + (trip.total_bill || 0),
                0
            );
        },
    },
    created() {
        this.fetchDetails();
    },
    methods: {
        formatNumber(value) {
            if (value == null) return "-";
            return value.toLocaleString();
        },
        fetchDetails() {
            axios
                .get(route("api.order.fetch.details", [this.orderId]), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.orderDetails = response.data.trips;
                    this.customerDetails = response.data.customer;
                    this.order = response.data.order;
                    this.form.total = this.totalBill;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        calculateProfit() {
            const { premium, commission } = this.form;
            this.form.profit = (premium || 0) - (commission || 0);
        },
        handleSubmit() {
            console.log("Form submitted:", this.form);
        },
        formatDate(dateString) {
            const options = { year: "numeric", month: "long", day: "numeric" };
            return new Date(dateString).toLocaleDateString(undefined, options);
        },

        // Convert numbers to words (optional logic)
        convertToWords(number) {
            if (number == null) return "No balance";
            // Add logic to convert number to words if needed
            return number.toString(); // For simplicity
        },

        processThis(id) {
            this.form.order_id = id;
            this.isLoading = true; // Show spinner during the process

            // Make the axios request to close the order
            axios
                .post(route("api.order.close"), this.form) // Post the form data to close the order
                .then((response) => {
                    this.isLoading = false; // Hide spinner once request is successful
                    toastr.success("Order closed successfully.");
                })
                .catch((error) => {
                    this.isLoading = false; // Hide spinner on error
                    toastr.error("Failed to close order.");
                    this.formErrors = error.response.data.errors || {}; 
                });
        },
    },
};
</script>

<style scoped>
.card-body {
    padding: 20px !important;
}
.card {
    margin-bottom: 15px;
}
@media (min-width: 768px) {
    .col-md-1 {
        flex: 0 0 auto !important;
        width: 1.333333% !important;
    }
}
</style>
