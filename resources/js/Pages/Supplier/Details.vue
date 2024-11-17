<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1 class="theme-text-color">{{ supplier.name }}</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/">R & R Coal</a>
                        </li>
                        <li class="breadcrumb-item">Supplier</li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a :href="route('suppliers')" class="btn btn-success mt-3">
                    <i class="bi bi-back"></i> Suppliers
                </a>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-body c-form-card">
                    <!-- PDF Download Button -->
                    <div class="d-flex justify-content-end mb-1">
                        <button
                            v-if="!pdfBtnSpinner"
                            @click="downloadPDF"
                            class="btn btn-primary mt-3 ml-3"
                        >
                            <i class="bi bi-file-earmark-pdf"></i> Download PDF
                        </button>
                        <button
                            v-else
                            class="btn btn-success"
                            type="button"
                            disabled
                        >
                            <span
                                class="spinner-border spinner-border-sm"
                            ></span>
                        </button>
                    </div>

                    <!-- Ledger Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Credit</th>
                                    <th>Debit</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(entry, index) in supplierDetails"
                                    :key="entry.id"
                                >
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ formatDate(entry.date) }}</td>
                                    <td>{{ entry.type }}</td>
                                    <td>{{ entry.credit }}</td>
                                    <td>{{ entry.debit }}</td>
                                    <td>{{ calculateBalance(index) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>
</template>

<script>
import Master from "../Layout/Master.vue";
import axios from "axios";
import Multiselect from "@vueform/multiselect";
import Datepicker from "@vuepic/vue-datepicker";
import { jsPDF } from "jspdf";
import autoTable from "jspdf-autotable"; // AutoTable plugin for jsPDF
import "@vuepic/vue-datepicker/dist/main.css";

export default {
    layout: Master,
    props: ["supplierId"],
    components: {
        Multiselect,
        Datepicker,
    },
    created() {
        this.fetchCustomerDetails();
    },
    data() {
        return {
            supplierDetails: [],
            supplier: [],
            pdfBtnSpinner: false,
        };
    },
    methods: {
        fetchCustomerDetails() {
            axios
                .get(route("api.supplier.fetch.details", this.supplierId), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.supplier = response.data.supplier;
                    this.supplierDetails = response.data.details;
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        formatDate(date) {
            const options = { year: "numeric", month: "long", day: "numeric" };
            return new Date(date).toLocaleDateString(undefined, options);
        },
        calculateBalance(index) {
            let balance = 0;
            for (let i = 0; i <= index; i++) {
                balance += this.supplierDetails[i].credit;
                balance -= this.supplierDetails[i].debit;
            }
            return this.formatCurrency(balance);
        },
        formatCurrency(value) {
            return new Intl.NumberFormat("en-PK", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(value);
        },
        downloadPDF() {
            this.pdfBtnSpinner = true;
            const doc = new jsPDF();

            // Add a title to the PDF
            doc.setFontSize(18);
            doc.setTextColor(40);
            doc.text(`${this.supplier.name} Ledger Details`, 14, 20);

            // Add AutoTable
            autoTable(doc, {
                startY: 30,
                head: [["#", "Date", "Type", "Credit", "Debit", "Balance"]],
                body: this.supplierDetails.map((entry, index) => [
                    index + 1,
                    this.formatDate(entry.date),
                    entry.type,
                    entry.credit,
                    entry.debit,
                    this.calculateBalance(index),
                ]),
                styles: {
                    fontSize: 10,
                    halign: "center",
                },
                headStyles: {
                    fillColor: [0, 0, 0], // Black header
                    textColor: [255, 255, 255], // White text
                },
            });

            // Save PDF
            doc.save(`${this.supplier.name}_Ledger.pdf`);
            this.pdfBtnSpinner = false;
        },
    },
};
</script>
