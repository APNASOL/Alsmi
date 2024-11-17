<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1 class="theme-text-color">{{ customerDetails.name }}</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/">R & R Coal</a>
                        </li>
                        <li class="breadcrumb-item">Order</li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a :href="route('orders')" class="btn btn-success mt-3">
                    <i class="bi bi-back"></i> Orders
                </a>

            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-body">
                     
                    <div class="container mt-2">
                        <!-- Card container -->
                        <div class="row justify-content-left">
                            <!-- Card 1: Mine Expense -->
                            <div class="col-md-3 col-12">
                                <div class="card border-0 shadow-sm rounded-3 bg-light">
                                    <div class="card-body">
                                        <h5 class="text-primary fw-bold">Mine Expense</h5>
                                        <p class="card-text fs-5 fw-bold text-dark">
                                            {{ formatCurrency(totalMineExpense) }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2: Tax -->
                            <div class="col-md-3 col-12">
                                <div class="card border-0 shadow-sm rounded-3 bg-light">
                                    <div class="card-body">
                                        <h5 class="text-primary fw-bold">Tax</h5>
                                        <p class="card-text fs-5 fw-bold text-dark">
                                            {{ formatCurrency(totalTax) }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 3: Transport -->
                            <div class="col-md-3 col-12">
                                <div class="card border-0 shadow-sm rounded-3 bg-light">
                                    <div class="card-body">
                                        <h5 class="text-primary fw-bold ">Transport</h5>
                                        <p class="card-text fs-5 fw-bold text-dark">
                                            {{ formatCurrency(totalTransport) }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 5: Truck Weight -->
                            <div class="col-md-3 col-12">
                                <div class="card border-0 shadow-sm rounded-3 bg-light">
                                    <div class="card-body">
                                        <h5 class="text-warning fw-bold">Truck Weight</h5>
                                        <p class="card-text fs-5 fw-bold text-dark">
                                            {{ totalTruckWeight }} Ton
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Section -->
                    <div class="col-md-12">
                        <div class="mb-1 text-end">

                            <button v-if="pdfBtnSpinner" @click="downloadPDF" class="btn btn-primary mt-3 ml-3">
                                <i class="bi bi-file-earmark-pdf"></i> Download PDF
                            </button>
                            <button v-else class="btn btn-success" type="button" disabled>

                                <span class="spinner-border spinner-border-sm"></span>
                            </button>

                        </div>
                        <div class="table-responsive">
                            <div class="col-md-12">
                                <table class="table table-striped" ref="orderTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Supplier</th>
                                            <th>Mine</th>
                                            <th>Weight</th>
                                            <th>Price</th>
                                            <th>Truck Price</th>
                                            <th>Vehicle</th>
                                            <th>Expense</th>
                                            <th>Transport</th>
                                            <th>Tax</th>
                                            <th>Total Bill</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(trip, index) in orderDetails" :key="trip.id">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ formatDate(trip.date) }}</td>
                                            <td>{{ trip.supplier_name }}</td>
                                            <td>{{ trip.mine_name }}</td>
                                            <td>{{ trip.truck_weight }}</td>
                                            <td>{{ formatCurrency(trip.price_per_ton) }}</td>
                                            <td>{{ formatCurrency(trip.truck_price) }}</td>
                                            <td>{{ trip.vehicle }}</td>
                                            <td>{{ formatCurrency(trip.mine_expense) }}</td>
                                            <td>{{ formatCurrency(trip.transport) }}</td>
                                            <td>{{ formatCurrency(trip.tax) }}</td>
                                            <td>{{ formatCurrency(trip.total_bill) }}</td>
                                            <td>{{ trip.status === 1 ? "Active" : "Inactive" }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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
        jsPDF, autoTable
    },

    created() {
        this.fetchDetails();
    },
    data() {
        return {
            orderDetails: [],
            customerDetails: [],
            pdfBtnSpinner:1,
        };
    },
    computed: {
        totalMineExpense() {
            return this.orderDetails.reduce((total, trip) => total + (trip.mine_expense || 0), 0);
        },
        totalTax() {
            return this.orderDetails.reduce((total, trip) => total + (trip.tax || 0), 0);
        },
        totalTransport() {
            return this.orderDetails.reduce((total, trip) => total + (trip.transport || 0), 0);
        },
        totalTruckPrice() {
            return this.orderDetails.reduce((total, trip) => total + (trip.truck_price || 0), 0);
        },
        totalTruckWeight() {
            return this.orderDetails.reduce((total, trip) => total + (trip.truck_weight || 0), 0);
        },
    },
    methods: {
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
}

    },
};
</script>

<style lang="scss" scoped>
.card-body {
    padding: 20px !important;
}
</style>
