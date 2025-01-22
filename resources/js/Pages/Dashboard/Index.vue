<template>
    <div>
        <main id="main" class="main">
            <div class="pagetitle">
                <h1>Dashboard</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.html">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </nav>
            </div>
            <!-- End Page Title -->

            <section class="section dashboard">
                <div class="row">
                    <!-- Top Boxes -->
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-xxl-4 col-md-4">
                                <div class="card info-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Cash In</h5>
                                        <div
                                            class="d-flex align-items-center justify-content-between"
                                        >
                                            <h6>
                                                {{ formatCurrency(cashIn) }}
                                            </h6>
                                            <span
                                                class="icon-bubble bg-success"
                                            >
                                                <i
                                                    class="bi bi-cash text-white"
                                                ></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-md-4">
                                <div class="card info-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Cash Out</h5>
                                        <div
                                            class="d-flex align-items-center justify-content-between"
                                        >
                                            <h6 class="text-danger">
                                                {{ formatCurrency(cashOut) }}
                                            </h6>
                                            <span class="icon-bubble bg-danger">
                                                <i
                                                    class="bi bi-cash-coin text-white"
                                                ></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-md-4">
                                <div class="card info-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Balance</h5>
                                        <div
                                            class="d-flex align-items-center justify-content-between"
                                        >
                                            <h6
                                                :class="{
                                                    'text-success':
                                                        balance >= 0,
                                                    'text-danger': balance < 0,
                                                }"
                                            >
                                                {{ formatCurrency(balance) }}
                                            </h6>
                                            <span
                                                :class="
                                                    balance >= 0
                                                        ? 'icon-bubble bg-success'
                                                        : 'icon-bubble bg-danger'
                                                "
                                            >
                                                <i
                                                    class="bi bi-bank text-white"
                                                ></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    Income vs Expense (Bar Chart - Last 6
                                    Months)
                                </h5>
                                <canvas id="barChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    Expense Distribution (Pie Chart - Last 6
                                    Months)
                                </h5>
                                <canvas id="pieChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    Income and Expense (Line Chart - Last 12
                                    Months)
                                </h5>
                                <canvas id="lineChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>

<script>
import Master from "../Layout/Master.vue";
import { Chart, registerables } from "chart.js";
import axios from "axios";

Chart.register(...registerables);

export default {
    layout: Master,
    data() {
        return {
            transactionEntries: [],
            cashIn: 0,
            cashOut: 0,
            balance: 0,
            last6MonthsData: [],
            last12MonthsData: [],
        };
    },
    methods: {
        formatCurrency(value) {
            return new Intl.NumberFormat("en-PK", {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            }).format(value);
        },

        fetchTransactionEntries() {
            axios
                .get(route("api.dashbaord.transaction.fetch"))
                .then((response) => {
                    console.log(response.data); // Debugging
                    this.transactionEntries = response.data || []; // Default to empty array if null
                    this.calculateStats();
                    this.updateCharts();
                })
                .catch((error) => {
                    console.error("Error fetching transactions:", error);
                });
        },
        calculateStats() {
            const currentDate = new Date();
            const last6Months = [];
            const last12Months = [];

            // Prepare arrays for last 6 and 12 months
            for (let i = 0; i < 12; i++) {
                const month = new Date();
                month.setMonth(currentDate.getMonth() - i);
                const monthLabel = `${
                    month.getMonth() + 1
                }-${month.getFullYear()}`;

                if (i < 6)
                    last6Months.push({
                        label: monthLabel,
                        income: 0,
                        expense: 0,
                    });
                last12Months.push({ label: monthLabel, income: 0, expense: 0 });
            }

            this.transactionEntries.forEach((entry) => {
                const entryDate = new Date(entry.transaction_date);
                if (isNaN(entryDate)) {
                    console.error(
                        "Invalid transaction date:",
                        entry.transaction_date
                    );
                    return;
                }

                const monthLabel = `${
                    entryDate.getMonth() + 1
                }-${entryDate.getFullYear()}`;

                // Process for last 6 months
                const last6Index = last6Months.findIndex(
                    (item) => item.label === monthLabel
                );
                if (last6Index !== -1) {
                    last6Months[last6Index].income +=
                        parseFloat(entry.cash_in) || 0;
                    last6Months[last6Index].expense +=
                        parseFloat(entry.cash_out) || 0;
                }

                // Process for last 12 months
                const last12Index = last12Months.findIndex(
                    (item) => item.label === monthLabel
                );
                if (last12Index !== -1) {
                    last12Months[last12Index].income +=
                        parseFloat(entry.cash_in) || 0;
                    last12Months[last12Index].expense +=
                        parseFloat(entry.cash_out) || 0;
                }
            });

            // Update data for charts and top boxes
            this.last6MonthsData = last6Months;
            this.last12MonthsData = last12Months;
            this.cashIn = last6Months.reduce(
                (sum, item) => sum + item.income,
                0
            );
            this.cashOut = last6Months.reduce(
                (sum, item) => sum + item.expense,
                0
            );
            this.balance = this.cashIn - this.cashOut;
        },
        updateCharts() {
            console.log("Updating charts...");
            console.log("Last 6 Months Data:", this.last6MonthsData);
            console.log("Last 12 Months Data:", this.last12MonthsData);
            console.log("Cash In:", this.cashIn, "Cash Out:", this.cashOut);

            // Destroy existing chart instances to prevent overlapping
            Object.values(Chart.instances).forEach((chart) => chart.destroy());

            // Destroy existing chart instances to prevent overlapping
            // Chart.instances.forEach((chart) => chart.destroy());

            // Bar Chart (Last 6 Months)
            const barCtx = document.getElementById("barChart").getContext("2d");
            new Chart(barCtx, {
                type: "bar",
                data: {
                    labels: this.last6MonthsData.map((item) => item.label),
                    datasets: [
                        {
                            label: "Income",
                            data: this.last6MonthsData.map(
                                (item) => item.income
                            ),
                            backgroundColor: "#4caf50",
                        },
                        {
                            label: "Expense",
                            data: this.last6MonthsData.map(
                                (item) => item.expense
                            ),
                            backgroundColor: "#f44336",
                        },
                    ],
                },
            });

            // Pie Chart (Last 6 Months)
            const pieCtx = document.getElementById("pieChart").getContext("2d");
            new Chart(pieCtx, {
                type: "pie",
                data: {
                    labels: ["Cash In", "Cash Out"],
                    datasets: [
                        {
                            data: [this.cashIn, this.cashOut],
                            backgroundColor: ["#4caf50", "#f44336"],
                        },
                    ],
                },
            });

            // Line Chart (Last 12 Months)
            const lineCtx = document
                .getElementById("lineChart")
                .getContext("2d");
            new Chart(lineCtx, {
                type: "line",
                data: {
                    labels: this.last12MonthsData.map((item) => item.label),
                    datasets: [
                        {
                            label: "Income",
                            data: this.last12MonthsData.map(
                                (item) => item.income
                            ),
                            borderColor: "#4caf50",
                            fill: false,
                        },
                        {
                            label: "Expense",
                            data: this.last12MonthsData.map(
                                (item) => item.expense
                            ),
                            borderColor: "#f44336",
                            fill: false,
                        },
                    ],
                },
            });
        },
    },

    mounted() {
        this.fetchTransactionEntries();
    },
};
</script>

<style lang="scss" scoped>
.info-card {
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    font-weight: bold !important;
}

.text-danger {
    color: #f44336 !important;
}

.text-success {
    color: #4caf50 !important;
}
.icon-bubble {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 55px; /* Adjust size */
    height: 55px; /* Adjust size */
    border-radius: 50%;
    font-size: 16px; /* Icon size */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style>
