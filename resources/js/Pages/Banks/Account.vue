<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1 class="theme-text-color">Accounts</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/dashboard">R & R Coal</a>
                        </li>
                        <li class="breadcrumb-item">Accounts</li>
                        <li class="breadcrumb-item active">Index</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button
                    class="btn btn-success mt-3"
                    data-bs-toggle="modal"
                    data-bs-target="#cashbookmodal"
                    @click="clearFields"
                >
                    <i class="bi bi-plus-lg"></i> Add New Entry
                </button>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title theme-text-color">Account Ledger</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Consumer Name</th>
                                    <th scope="col">Process Type</th>
                                    <th scope="col">Notes</th>
                                    <th scope="col">Cash In</th>
                                    <th scope="col">Cash Out</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Ref.No</th>
                                    <th scope="col">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(entry, index) in Accounts"
                                    :key="index"
                                >
                                    <th scope="row">{{ index + 1 }}</th>
                                    <td>
                                        {{ entry.consumer_name }} <br />
                                        <small
                                            >{{ entry.bank_title }} <br />
                                            {{ entry.bank_account }}</small
                                        >
                                    </td>
                                    <td>{{ entry.process_type }}</td>
                                    <td>{{ entry.note }}</td>
                                    <td
                                        :title="
                                            convertToWords(entry.cash_in || 0)
                                        "
                                    >
                                        {{ formatNumber(entry.cash_in || 0) }}
                                    </td>
                                    <td
                                        :title="
                                            convertToWords(entry.cash_out || 0)
                                        "
                                    >
                                        {{ formatNumber(entry.cash_out || 0) }}
                                    </td>

                                    <td>{{ formatDate(entry.date) }}</td>
                                    <td>{{ entry.ref_no || "-" }}</td>
                                    <td :title="convertToWords(entry.balance)">
                                        {{ formatNumber(entry.balance) }}
                                    </td>
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
import "@vuepic/vue-datepicker/dist/main.css";

export default {
    layout: Master,
    props: ["customerId"],
    components: {
        Multiselect,
        Datepicker,
    },
    created() {
        this.fetchDetails();
    },
    data() {
        return {
            Accounts: [], // Stores account entries with balance
            customer: {}, // Stores customer details
            pdfBtnSpinner: false, // Tracks the loading state of PDF generation
        };
    },
    methods: {
        // Fetch account details
        fetchDetails() {
            axios
                .get(route("api.fetch.account.details"), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    let runningBalance = 0;
                    this.Accounts = response.data.map((entry) => {
                        const cashIn = entry.cash_in || 0;
                        const cashOut = entry.cash_out || 0;
                        runningBalance += cashIn - cashOut;
                        return {
                            ...entry,
                            cash_in: cashIn,
                            cash_out: cashOut,
                            balance: runningBalance,
                        };
                    });
                })
                .catch((error) => {
                    toastr.error(
                        error.response?.data?.message ||
                            "Error fetching account details."
                    );
                });
        },
        formatDate(date) {
            const options = { year: "numeric", month: "long", day: "numeric" };
            return new Date(date).toLocaleDateString(undefined, options);
        },
        // Format a number with commas
        formatNumber(value) {
            if (value == null) return "-"; // Handle null or undefined values
            return value.toLocaleString();
        },
        // Convert a number to words, supporting numbers up to crores
        convertToWords(number) {
            if (number == null) return "No balance";

            const units = [
                "Zero",
                "One",
                "Two",
                "Three",
                "Four",
                "Five",
                "Six",
                "Seven",
                "Eight",
                "Nine",
            ];
            const teens = [
                "Ten",
                "Eleven",
                "Twelve",
                "Thirteen",
                "Fourteen",
                "Fifteen",
                "Sixteen",
                "Seventeen",
                "Eighteen",
                "Nineteen",
            ];
            const tens = [
                "",
                "",
                "Twenty",
                "Thirty",
                "Forty",
                "Fifty",
                "Sixty",
                "Seventy",
                "Eighty",
                "Ninety",
            ];
            const scales = ["", "Thousand", "Lakh", "Crore"];

            function toWords(n) {
                if (n < 10) return units[n];
                if (n >= 10 && n < 20) return teens[n - 10];
                if (n >= 20 && n < 100)
                    return (
                        tens[Math.floor(n / 10)] +
                        (n % 10 > 0 ? " " + units[n % 10] : "")
                    );
                if (n >= 100 && n < 1000)
                    return (
                        units[Math.floor(n / 100)] +
                        " Hundred" +
                        (n % 100 > 0 ? " and " + toWords(n % 100) : "")
                    );
                return "";
            }

            function largeNumberToWords(n) {
                let result = "";
                let scaleIndex = 0;

                while (n > 0) {
                    const part = n % 1000;
                    if (part > 0) {
                        const scale = scales[scaleIndex];
                        result =
                            toWords(part) +
                            (scale ? " " + scale : "") +
                            (result ? " " + result : "");
                    }
                    n = Math.floor(n / 1000);
                    scaleIndex++;
                }
                return result;
            }

            // Handle negative numbers
            const absoluteNumber = Math.abs(number);
            const words = largeNumberToWords(absoluteNumber);
            return number < 0 ? words + " Negative" : words;
        },
    },
};
</script>
