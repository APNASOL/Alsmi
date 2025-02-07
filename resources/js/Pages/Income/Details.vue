<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1 class="theme-text-color">Income From - {{ income_type_name }}</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/">Alsmi</a>
                        </li>
                        <li class="breadcrumb-item">Income</li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </nav>
            </div>
        </div>
        <section class="section">
            <div class="card p-4">
                <div class="card-body c-form-card">
                    <div class="table-responsive">
                        <table id="expenseTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Credit</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(entry, index) in income_records"
                                    :key="entry.id"
                                >
                                    <td>{{ index + 1 }}</td>
                                    <td>
                                        {{ formatDate(entry.transaction_date) }}
                                    </td>
                                    <td>{{ entry.amount }}</td>
                                     
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

import jsPDF from "jspdf";
import "jspdf-autotable";

import "@vuepic/vue-datepicker/dist/main.css";

export default {
    layout: Master,
    props: ["income_records","income_type_name"],
    mounted() {
        this.$nextTick(() => {
            $("#expenseTable").DataTable();
        });
    },
    methods: {
        // Helper function to format dates properly
        formatDate(date) {
            const d = new Date(date);
            return d.toLocaleDateString("en-US", {
                day: "2-digit",
                month: "short",
                year: "numeric",
            });
        },
    },
};
</script>
