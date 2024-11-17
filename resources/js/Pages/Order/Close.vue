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
                    <div class="container mt-2">
                        <h5 class="text-danger fw-bold">
                            Durtaion {{ formatDate(order.start_date) }} -
                            {{ formatDate(order.end_date) }}
                        </h5>
                        <!-- Card container -->
                        <div class="row justify-content-left">
                            <!-- Card 1: Mine Expense -->
                            <div class="col-md-3 col-12">
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
                                            {{
                                                formatCurrency(totalMineExpense)
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2: Tax -->
                            <div class="col-md-3 col-12">
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
                                            {{ formatCurrency(totalTax) }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 3: Transport -->
                            <div class="col-md-3 col-12">
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
                                            {{ formatCurrency(totalTransport) }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 5: Truck Weight -->
                            <div class="col-md-3 col-12">
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

                            <!-- Card: Price Per Ton -->
                            <div class="col-md-3 col-12">
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
                                            {{
                                                formatCurrency(totalPricePerTon)
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card: Truck Price -->
                            <div class="col-md-3 col-12">
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
                                                formatCurrency(totalTruckPrice)
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card: Total Bill -->
                            <div class="col-md-3 col-12">
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
                                            {{ formatCurrency(totalBill) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Section -->
                    <div class="col-md-12"></div>
                </div>
            </div>
        </section>
    </main>
</template>

<script>
import Master from "../Layout/Master.vue";

import axios from "axios";
import { jsPDF } from "jspdf";
import autoTable from "jspdf-autotable";

export default {
    layout: Master,
    props: ["orderId"],
    components: {
        jsPDF,
        autoTable,
    },

    created() {
        this.fetchDetails();
    },
    data() {
        return {
            orderDetails: [],
            order: [],
            customerDetails: [],
            pdfBtnSpinner: 1,
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
        totalTruckPrice() {
            return this.orderDetails.reduce(
                (total, trip) => total + (trip.truck_price || 0),
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

        totalBill() {
            return this.orderDetails.reduce(
                (total, trip) => total + (trip.total_bill || 0),
                0
            );
        },
    },
    methods: {
        closeOrder() {
            this.pdfBtnSpinner = 0; // Start spinner
            this.$inertia.visit(route("order.close", [this.orderId]));
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
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        formatDate(dateString) {
            const options = { year: "numeric", month: "long", day: "numeric" };
            return new Date(dateString).toLocaleDateString(undefined, options);
        },
        formatCurrency(value) {
            return new Intl.NumberFormat("en-PK", {
                style: "currency",
                currency: "PKR",
                currencyDisplay: "symbol",
            }).format(value);
        },

        // Method to download PDF
        downloadPDF() {
            this.pdfBtnSpinner = 0;
            const doc = new jsPDF();

            // Add customer details as title
            doc.setFontSize(18);
            doc.setTextColor(40);
            doc.text(`${this.customerDetails.name} Order Details`, 14, 20);

            // Table headers
            const columns = [
                "#",
                "Date",
                "Supplier",
                "Mine",
                "Weight",
                "Price",
                "Truck Price",
                "Vehicle",
                "Expense",
                "Transport",
                "Tax",
                "Total Bill",
            ];

            // Table rows
            const rows = this.orderDetails.map((trip, index) => [
                index + 1,
                this.formatDate(trip.date),
                trip.supplier_name,
                trip.mine_name,
                trip.truck_weight,
                trip.price_per_ton,
                trip.truck_price,
                trip.vehicle,
                trip.mine_expense,
                trip.transport,
                trip.tax,
                trip.total_bill,
            ]);

            // Add table to the PDF
            autoTable(doc, {
                head: [columns],
                body: rows,
                startY: 30,
                theme: "grid",
                styles: {
                    fontSize: 10,
                    halign: "center", // Align text to center in cells
                },
                headStyles: {
                    fillColor: [0, 0, 0], // Black header background
                    textColor: [255, 255, 255], // White text
                },
            });

            // Save the PDF file
            doc.save(`${this.customerDetails.name}-order-details.pdf`);
            this.pdfBtnSpinner = 1;
        },
    },
};
</script>

<style lang="scss" scoped>
.card-body {
    padding: 20px !important;
}
.card-body {
    padding: 20px !important;
}

.card {
    margin-bottom: 15px;
}
</style>
