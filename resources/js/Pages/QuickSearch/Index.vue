<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1 class="theme-text-color">Quick Search</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/dashboard">R & R Coal</a>
                        </li>
                        <li class="breadcrumb-item">Students</li>
                        <li class="breadcrumb-item active">Search</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button
                    class="btn btn-success mt-3"
                    data-bs-toggle="modal"
                    data-bs-target="#studentmodal"
                >
                    <i class="bi bi-plus-lg"></i> Button
                </button>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title theme-text-color">
                                Single Student
                            </h5>
                            <div class="card card-body p-3">
                                <div class="row g-3">
                                    <div class="col-md-6 col-12">
                                        <label
                                            for="inputNanme4"
                                            class="form-label"
                                            >Registration No</label
                                        >
                                        <input
                                            type="regNo"
                                            class="form-control"
                                            id="regNo"
                                            :class="{
                                                'invalid-bg': formErrors.regNo,
                                            }"
                                            v-model="form.regNo"
                                        />

                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.regNo"
                                        >
                                            {{ formErrors.regNo[0] }}
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label
                                            for="inputNanme4"
                                            class="form-label"
                                            >Name</label
                                        >
                                        <input
                                            type="name"
                                            class="form-control"
                                            id="name"
                                            :class="{
                                                'invalid-bg': formErrors.name,
                                            }"
                                            v-model="form.name"
                                        />

                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.name"
                                        >
                                            {{ formErrors.name[0] }}
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label
                                            for="inputNanme4"
                                            class="form-label"
                                            >Father</label
                                        >
                                        <input
                                            type="father_name"
                                            class="form-control"
                                            id="father_name"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.father_name,
                                            }"
                                            v-model="form.father_name"
                                        />

                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.father_name"
                                        >
                                            {{ formErrors.father_name[0] }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label>
                                            {{ "Contact" }}
                                        </label>
                                        <input
                                            type="contact"
                                            class="form-control"
                                            id="contact"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.contact,
                                            }"
                                            v-model="form.contact"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.contact"
                                        >
                                            {{ formErrors.contact[0] }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label>
                                            {{ "CNIC" }}
                                        </label>
                                        <input
                                            type="cnic"
                                            class="form-control"
                                            id="cnic"
                                            :class="{
                                                'invalid-bg': formErrors.cnic,
                                            }"
                                            v-model="form.cnic"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.cnic"
                                        >
                                            {{ formErrors.cnic[0] }}
                                        </div>
                                    </div>

                                    <div class="mt-5">
                                        <button
                                            type="submit"
                                            class="btn btn-success"
                                            v-if="formStatus == 1"
                                            @click="submit"
                                        >
                                            Search
                                        </button>
                                        <button
                                            class="btn btn-success"
                                            type="button"
                                            disabled
                                            v-else
                                        >
                                            Searching
                                            <span
                                                class="spinner-border spinner-border-sm"
                                                role="status"
                                                aria-hidden="true"
                                            ></span>
                                        </button>
                                    </div>
                                </div>
                                <!-- Vertical Form -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="card-title theme-text-color">
                                Batch Wise
                            </h5>

                            <div class="card card-body p-3">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label>{{ "Program" }}</label>
                                        <Multiselect
                                            v-model="form.program"
                                            :options="programOptions"
                                            :placeholder="'Program'"
                                            :searchable="true"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.program,
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.program"
                                        >
                                            {{ formErrors.program[0] }}
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label>{{ "Session" }}</label>
                                        <Multiselect
                                            v-model="form.program"
                                            :options="programOptions"
                                            :placeholder="'Session'"
                                            :searchable="true"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.program,
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.program"
                                        >
                                            {{ formErrors.program[0] }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label>{{ "Batch" }}</label>
                                        <Multiselect
                                            v-model="form.program"
                                            :options="programOptions"
                                            :placeholder="'Batch'"
                                            :searchable="true"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.program,
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.program"
                                        >
                                            {{ formErrors.program[0] }}
                                        </div>
                                    </div>

                                    <div class="mt-5">
                                        <button
                                            type="submit"
                                            class="btn btn-success"
                                            v-if="formStatus == 1"
                                            @click="submit"
                                        >
                                            Search
                                        </button>
                                        <button
                                            class="btn btn-success"
                                            type="button"
                                            disabled
                                            v-else
                                        >
                                            Searching
                                            <span
                                                class="spinner-border spinner-border-sm"
                                                role="status"
                                                aria-hidden="true"
                                            ></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-body p-3">
                        <div class="row p-3">
                            <h5 class="card-title theme-text-color">
                                Searched Result
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Father</th>
                                            <th>Batch</th>
                                            <th>Session</th>
                                            <th>Semester</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Ali Khan</td>
                                            <td>Gul Ahmad</td>
                                            <td>2023-2025</td>
                                            <td>Fall</td>
                                            <td>4th</td>
                                        </tr>
                                        <tr>
                                            <td>Ali Khan</td>
                                            <td>Gul Ahmad</td>
                                            <td>2023-2025</td>
                                            <td>Fall</td>
                                            <td>4th</td>
                                        </tr>
                                        <tr>
                                            <td>Ali Khan</td>
                                            <td>Gul Ahmad</td>
                                            <td>2023-2025</td>
                                            <td>Fall</td>
                                            <td>4th</td>
                                        </tr>
                                        <tr>
                                            <td>Ali Khan</td>
                                            <td>Gul Ahmad</td>
                                            <td>2023-2025</td>
                                            <td>Fall</td>
                                            <td>4th</td>
                                        </tr>
                                    </tbody>
                                    <tfoot></tfoot>
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
import Multiselect from "@vueform/multiselect";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import axios from "axios";
export default {
    layout: Master,
    props: [],
    components: {
        Multiselect,
        Datepicker,
    },

    created() {
        // this.function();
        this.pluckRequiredData();
    },
    setup() {
        return {
            form: {
                name: "",
                father_name: "",
                regNo: "",
                cnic: "",
                contact: "",
            },
            formErrors: [],
            formStatus: 1,
            programOptions: [],
        };
    },
    methods: {
        pluckRequiredData() {
            axios
                .get(route("api.student.required.models.pluck"), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.programOptions = response.data.program;
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        // function() {
        //     axios
        //         .get(route("api.abc", id), {
        //             headers: {
        //                 Authorization: "Bearer " + this.$page.props.auth_token,
        //             },
        //         })
        //         .then((response) => {
        //             this.ABC = response.data;
        //         })
        //         .catch((error) => {
        //             toastr.error(error.response.data.message);
        //         });
        // },
    },
};
</script>

<style lang="scss" scoped></style>
