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
                    <!-- Filter -->

                    <!-- Top Boxes -->
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="card info-card bg-white h-100">
                                    <!-- Ensure same height -->
                                    <div class="card-body d-flex flex-column">
                                        <div
                                            class="d-flex align-items-center justify-content-between"
                                        >
                                            <h6>Incomes</h6>
                                        </div>
                                        <div class="flex-grow-1">
                                            <!-- Ensures content stretches -->
                                            <h5 class="card-title">
                                                Total: {{ incomeTypeTotal }}
                                            </h5>

                                            <div
                                                v-for="income in incomeTypeDetails"
                                                :key="income.id"
                                                class="d-flex justify-content-between border-bottom py-1"
                                            >
                                                <span>{{ income.name }}</span>
                                                <span>
                                                    {{
                                                        income.total_income_type
                                                    }}
                                                    ({{
                                                        income.percentage_of_total
                                                    }}%)
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="card info-card bg-white h-100">
                                    <!-- Ensure same height -->
                                    <div class="card-body d-flex flex-column">
                                        <div
                                            class="d-flex align-items-center justify-content-between"
                                        >
                                            <h6>Expense</h6>
                                        </div>
                                        <div class="flex-grow-1">
                                            <!-- Ensures content stretches -->
                                            <h5 class="card-title">
                                                Total: {{ expenseTypeTotal }}
                                            </h5>

                                            <div
                                                v-for="expense in expenseTypeDetails"
                                                :key="expense.id"
                                                class="d-flex justify-content-between border-bottom py-1"
                                            >
                                                <span>{{ expense.name }}</span>
                                                <span>
                                                    {{
                                                        expense.total_expense_type
                                                    }}
                                                    ({{
                                                        expense.percentage_of_total
                                                    }}%)
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mb-3 mt-3">
                        <div class="col-md-3">
                            <Multiselect
                                v-model="selectedFilter"
                                :options="filterOptions"
                                :searchable="true"
                                @select="applyFilter"
                                placeholder="Filter By"
                            />
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-xxl-4 col-md-4">
                                <div class="card info-card bg-white">
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
                                <div class="card info-card bg-white">
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
                                <div class="card info-card bg-white">
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
                                    Income vs Expense (Bar Chart - Selected
                                    Range)
                                </h5>
                                <canvas id="barChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    Income and Expense (Line Chart - Selected
                                    Range)
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
import Multiselect from "@vueform/multiselect";
import axios from "axios";

Chart.register(...registerables);

export default {
    layout: Master,
    components: {
        Multiselect,
    },
    data() {
        return {
            transactionEntries: [],
            incomeTypeDetails: [],
            incomeTypeTotal: 0,
            expenseTypeDetails:[],
            expenseTypeTotal: 0,

            expenseDetails: [],
            cashIn: 0,
            cashOut: 0,
            balance: 0,
            last6MonthsData: [],
            last12MonthsData: [],
            selectedFilter: "3", // Default to last 3 months
            titleCashIn: "Current Month Cash In",
            titleCashIn: "Current Month Cash Out",
            titleCashIn: "Last 3 Months Cash In",
            titleCashOut: "Last 3 Months Cash Out",
            titleBalance: "Last 3 Months Balance",
            filterLabel: "Last 3 Months",
            filterOptions: [
                { value: "1", label: "Current Month" },
                { value: "3", label: "Last 3 Months" },
                { value: "6", label: "Last 6 Months" },
                { value: "12", label: "Last 1 Year" },
                // { value: "all", label: "Overall" },
            ],
        };
    },
    methods: {
        formatCurrency(value) {
            return new Intl.NumberFormat("en-PK", {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            }).format(value);
        },

        fetchExpenseDetails() {
            axios
                .get(route("api.dashboard.expense.details.fetch"))
                .then((response) => {
                    this.expenseDetails = response.data || [];
                    this.expenseTypeDetails = response.data.expense_types;
                    this.expenseTypeTotal = response.data.total_expense;
                })
                .catch((error) => {
                    console.error("Error fetching transactions:", error);
                });
        },
        fetchIncomeDetails() {
            axios
                .get(route("api.dashboard.income.details.fetch"))
                .then((response) => {
                     
                    this.incomeTypeDetails = response.data.income_types;
                    this.incomeTypeTotal = response.data.total_income;
                })
                .catch((error) => {
                    console.error("Error fetching transactions:", error);
                });
        },
        applyFilter() {
            if (this.selectedFilter === "all") {
                // Set filter label and titles for overall data
                this.filterLabel = "Overall";
                this.titleCashIn = "Overall Cash In";
                this.titleCashOut = "Overall Cash Out";
                this.titleBalance = "Overall Balance";

                // Pass all entries to calculateStats
                this.calculateStats(this.transactionEntries);
            } else {
                // Handle month-based filtering
                const filterMonths = parseInt(this.selectedFilter);
                const currentDate = new Date();

                const filteredData = this.transactionEntries.filter((entry) => {
                    const entryDate = new Date(entry.transaction_date);
                    const monthDifference =
                        (currentDate.getFullYear() - entryDate.getFullYear()) *
                            12 +
                        currentDate.getMonth() -
                        entryDate.getMonth();
                    return monthDifference < filterMonths;
                });

                this.filterLabel = `Last ${this.selectedFilter} Months`;
                this.titleCashIn = `${this.filterLabel} Cash In`;
                this.titleCashOut = `${this.filterLabel} Cash Out`;
                this.titleBalance = `${this.filterLabel} Balance`;

                this.calculateStats(filteredData);
            }

            this.updateCharts();
        },
        updateCharts() {
            // Check and destroy existing charts
            if (this.barChart) {
                this.barChart.destroy();
                this.barChart = null;
            }
            if (this.lineChart) {
                this.lineChart.destroy();
                this.lineChart = null;
            }

            if (this.selectedFilter === "all") {
                // Aggregate totals for cash in and cash out
                const totalCashIn = this.transactionEntries.reduce(
                    (sum, entry) => sum + parseFloat(entry.cash_in || 0),
                    0
                );

                const totalCashOut = this.transactionEntries.reduce(
                    (sum, entry) => sum + parseFloat(entry.cash_out || 0),
                    0
                );
 
                // Bar Chart (Overall Data for All)
                const barCtx = document
                    .getElementById("barChart")
                    .getContext("2d");
                this.barChart = new Chart(barCtx, {
                    type: "bar",
                    data: {
                        labels: ["Cash In", "Cash Out"], // Overall categories
                        datasets: [
                            {
                                label: "Overall Data",
                                data: [totalCashIn, totalCashOut],
                                backgroundColor: ["#4caf50", "#f44336"], // Green for Cash In, Red for Cash Out
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false,
                            },
                            tooltip: {
                                callbacks: {
                                    label: (context) =>
                                        `${
                                            context.label
                                        }: ${this.formatCurrency(context.raw)}`,
                                },
                            },
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: "Transaction Type",
                                },
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: "Amount",
                                },
                                beginAtZero: true,
                            },
                        },
                    },
                });
            } else {
                // Existing logic for monthly filters
                const barCtx = document
                    .getElementById("barChart")
                    .getContext("2d");
                this.barChart = new Chart(barCtx, {
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
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: "top",
                            },
                            tooltip: {
                                callbacks: {
                                    label: (context) =>
                                        `${
                                            context.dataset.label
                                        }: ${this.formatCurrency(context.raw)}`,
                                },
                            },
                        },
                    },
                });

                const lineCtx = document
                    .getElementById("lineChart")
                    .getContext("2d");

                this.lineChart = new Chart(lineCtx, {
                    type: "line", // Line chart type
                    data: {
                        labels: this.last12MonthsData.map((item) => item.label), // X-axis labels: Months or other periods
                        datasets: [
                            {
                                label: "Income", // Label for Income line
                                data: this.last12MonthsData.map(
                                    (item) => item.income
                                ), // Data for Income
                                borderColor: "#4caf50", // Green line for Income
                                fill: false, // No fill under the line
                                tension: 0.4, // Smooth curve
                            },
                            {
                                label: "Expense", // Label for Expense line
                                data: this.last12MonthsData.map(
                                    (item) => item.expense
                                ), // Data for Expense
                                borderColor: "#f44336", // Red line for Expense
                                fill: false, // No fill under the line
                                tension: 0.4, // Smooth curve
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: "top", // Legend position at the top
                            },
                            tooltip: {
                                callbacks: {
                                    label: (context) =>
                                        `${
                                            context.dataset.label
                                        }: ${this.formatCurrency(context.raw)}`, // Format tooltip currency
                                },
                            },
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: "Month", // X-axis title (e.g., Month)
                                },
                                ticks: {
                                    autoSkip: true,
                                    maxTicksLimit: 12, // Limit the number of ticks on the x-axis
                                },
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: "Amount", // Y-axis title (e.g., Amount)
                                },
                                beginAtZero: true, // Start y-axis from 0
                            },
                        },
                    },
                });
            }
        },

        fetchTransactionEntries() {
            axios
                .get(route("api.dashbaord.transaction.fetch"))
                .then((response) => {
                    this.transactionEntries = response.data || [];
                    this.applyFilter();
                })
                .catch((error) => {
                    console.error("Error fetching transactions:", error);
                });
        },

        calculateStats() {
            const currentDate = new Date();
            const filteredEntries = this.transactionEntries.filter((entry) => {
                const entryDate = new Date(entry.transaction_date);
                if (isNaN(entryDate)) return false;

                if (this.selectedFilter === "all") return true;

                const filterMonths = parseInt(this.selectedFilter);
                const pastDate = new Date();
                pastDate.setMonth(currentDate.getMonth() - filterMonths);

                return entryDate >= pastDate;
            });

            this.cashIn = filteredEntries.reduce(
                (sum, entry) => sum + (parseFloat(entry.cash_in) || 0),
                0
            );
            this.cashOut = filteredEntries.reduce(
                (sum, entry) => sum + (parseFloat(entry.cash_out) || 0),
                0
            );
            this.balance = this.cashIn - this.cashOut;

            this.last6MonthsData = this.groupDataByMonths(
                filteredEntries,
                this.selectedFilter
            );
            this.last12MonthsData = this.groupDataByMonths(
                filteredEntries,
                this.selectedFilter
            );
        },

        applyFilter() {
            const filterMonths =
                this.selectedFilter === "all"
                    ? Infinity
                    : parseInt(this.selectedFilter);
            const currentDate = new Date();

            const filteredData = this.transactionEntries.filter((entry) => {
                const entryDate = new Date(entry.transaction_date);
                const monthDifference =
                    (currentDate.getFullYear() - entryDate.getFullYear()) * 12 +
                    currentDate.getMonth() -
                    entryDate.getMonth();
                return monthDifference < filterMonths;
            });

            this.calculateStats(filteredData);
            this.updateCharts();
        },

        groupDataByMonths(entries, months) {
            const groupedData = [];
            const currentDate = new Date();

            const formatMonth = new Intl.DateTimeFormat("en-US", {
                month: "short", // Abbreviated month name (e.g., Jan, Feb)
                year: "numeric", // Full year (e.g., 2025)
            });

            for (let i = 0; i < months; i++) {
                const month = new Date();
                month.setMonth(currentDate.getMonth() - i);
                const monthLabel = formatMonth.format(month);

                groupedData.push({
                    label: monthLabel,
                    income: 0,
                    expense: 0,
                });
            }

            entries.forEach((entry) => {
                const entryDate = new Date(entry.transaction_date);
                const monthLabel = formatMonth.format(entryDate);

                const groupIndex = groupedData.findIndex(
                    (item) => item.label === monthLabel
                );

                if (groupIndex !== -1) {
                    groupedData[groupIndex].income +=
                        parseFloat(entry.cash_in) || 0;
                    groupedData[groupIndex].expense +=
                        parseFloat(entry.cash_out) || 0;
                }
            });

            return groupedData;
        },
    },
    mounted() {
        this.fetchTransactionEntries();
    },
    watch: {
        transactionEntries() {
            this.applyFilter();
        },
        selectedFilter() {
            this.applyFilter();
        },
    },
    created() {
        this.fetchIncomeDetails();
        this.fetchExpenseDetails();
    },
};
</script>

<style scoped>
@import "@vueform/multiselect/themes/default.css";
.info-card {
    background: #f8f9fa;
    border: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    padding: 20px;
}

.icon-bubble {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
canvas {
    width: 100% !important;
    height: 400px !important;
}
</style>
