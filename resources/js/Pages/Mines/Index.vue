<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1 class="theme-text-color">Mines</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/dashboard">R & R Coal</a>
                        </li>
                        <li class="breadcrumb-item">Mines</li>
                        <li class="breadcrumb-item active">Index</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button
                    class="btn btn-success mt-3"
                    data-bs-toggle="modal"
                    data-bs-target="#studentmodal"
                    @click="clearFields"
                >
                    <i class="bi bi-plus-lg"></i> Add New Mine
                </button>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title theme-text-color">All Mines</h5>

                    <!-- Table with stripped rows -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>

                                <th scope="col">Name</th>
                                <th scope="col">Number</th>
                                <th scope="col">Place</th>
                                <th scope="col">Security</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(mine, index) in Mines" :key="mine.id">
                                <th scope="row">{{ index + 1 }}</th>

                                <td>
                                    {{ mine.mine_name }}
                                </td>
                                <td>{{ mine.mine_no }}</td>
                                <td>{{ mine.mine_place }}</td>
                                <td>{{ mine.security }}</td>
                                <td>{{ mine.status }}</td>
                                <td>
                                    <div class="btn-group">
                                        <Link
                                            type="button"
                                            class="btn btn-sm fs-6"
                                            :title="'Edit'"
                                            data-bs-toggle="modal"
                                            data-bs-target="#studentmodal"
                                            @click="
                                                showMine(mine.id), clearFields()
                                            "
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </Link>
                                        <DeleteModal
                                            :deleteId="mine.id"
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
            <!-- Mine modal -->
            <div
                class="modal fade"
                id="studentmodal"
                tabindex="-1"
                aria-labelledby="exampleModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="section-title mt-1">
                                <h5 class="c-theme-red">Mine</h5>
                            </div>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            ></button>
                        </div>
                        <div class="modal-body">
                            <!-- Vertical Form -->
                            <div class="card card-body p-3">
                                <div class="row g-3">
                                    <div class="col-12 col-md-12">
                                        <label>{{ "Choose status" }} </label>
                                        <br />
                                        <div class="d-inline-block me-2">
                                            {{ "Off" }}
                                        </div>
                                        <div
                                            class="form-check form-switch d-inline-block"
                                        >
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                :class="{
                                                    'invalid-bg':
                                                        formErrors.status,
                                                }"
                                                v-model="form.status"
                                                checked
                                            />
                                            <label class="form-check-label">
                                                {{ "On" }}
                                            </label>
                                        </div>

                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.status"
                                        >
                                            {{ formErrors.status[0] }}
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label
                                            for="inputNanme4"
                                            class="form-label"
                                            >Name</label
                                        >
                                        <input
                                            type="mine_name"
                                            class="form-control"
                                            id="mine_name"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.mine_name,
                                            }"
                                            v-model="form.mine_name"
                                        />

                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.mine_name"
                                        >
                                            {{ formErrors.mine_name[0] }}
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label
                                            for="inputNanme4"
                                            class="form-label"
                                            >Mine number</label
                                        >
                                        <input
                                            type="number"
                                            class="form-control"
                                            id="mine_no"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.mine_no,
                                            }"
                                            v-model="form.mine_no"
                                        />

                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.mine_no"
                                        >
                                            {{ formErrors.mine_no[0] }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label>
                                            {{ "Mine Place" }}
                                        </label>
                                        <input
                                            type="mine_place"
                                            class="form-control"
                                            id="mine_place"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.mine_place,
                                            }"
                                            v-model="form.mine_place"
                                        />

                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.mine_place"
                                        >
                                            {{ formErrors.mine_place[0] }}
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label>{{ "Security" }}</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            id="security"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.security,
                                            }"
                                            v-model="form.security"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.security"
                                        >
                                            {{ formErrors.security[0] }}
                                        </div>
                                    </div>

                                    <div class="mt-5">
                                        <button
                                            type="submit"
                                            class="btn btn-success"
                                            v-if="formStatus == 1"
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
                                                role="status"
                                                aria-hidden="true"
                                            ></span>
                                        </button>
                                    </div>
                                </div>
                                <!-- Vertical Form -->
                            </div>
                        </div>
                    </div>
                </div>
                <button
                    hidden="hidden"
                    data-bs-toggle="modal"
                    data-bs-target="#studentmodal"
                    title="Delete"
                    ref="closeModal"
                ></button>
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
    components: {
        Multiselect,
        Datepicker,
    },
    created() {
        this.fetchMines();
    },

    data() {
        return {
            Mines: [],
            form: {
                id: "",
                mine_name: "",
                mine_no: "",
                mine_place: "",
                security: "",
                status: "",
            },
            formErrors: [],
            formStatus: 1,
            mine_id: "",
            genderOptions: ["Male", "Female", "Other"],
            programOptions: [],
        };
    },
    methods: {
        fetchMines() {
            axios
                .get(route("api.mine.fetch"), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.Mines = response.data;
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },

        submit() {
            this.formStatus = 0;
            let formData = new FormData();
            formData.append("id", this.form.id); //this id for updating the record.

            formData.append(
                "mine_name",
                this.form.mine_name == "" || this.form.mine_name == null
                    ? ""
                    : this.form.mine_name
            );
            formData.append(
                "mine_no",
                this.form.mine_no == "" || this.form.mine_no == null
                    ? ""
                    : this.form.mine_no
            );
            formData.append(
                "mine_place",
                this.form.mine_place == "" || this.form.mine_place == null
                    ? ""
                    : this.form.mine_place
            );

            formData.append(
                "security",
                this.form.security == "" || this.form.security == null
                    ? ""
                    : this.form.security
            );

            formData.append(
                "status",
                this.form.status === "" || this.form.status === null
                    ? ""
                    : this.form.status
                    ? "true"
                    : "false"
            );

            axios
                .post(route("api.mine.store"), formData, {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.formStatus = 1;
                    toastr.success("Mine record saved successfully");
                    this.fetchMines();
                    this.$refs.closeModal.click();
                    this.mine_id = "";
                })
                .catch((error) => {
                    this.formStatus = 1;
                    toastr.error(error.response.data.message);
                    this.formErrors = error.response.data.errors;
                });
        },

        clearFields() {
            this.formStatus = 1;
            this.form.id = "";
            this.form.mine_name = "";
            this.form.mine_no = "";
            this.form.status = "";
            this.form.security = "";
            this.form.mine_place = "";
            this.formErrors = [];
        },
        deleteThis(id) {
            axios
                .delete(route("api.mine.delete", id), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then(() => {
                    toastr.success("User deleted successfully.");
                    this.fetchMines();
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        showMine(mine_id) {
            axios
                .get(route("api.mine.show", mine_id), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.form.id = response.data.id;
                    this.form.mine_name = response.data.mine_name;
                    this.form.mine_no = response.data.mine_no;
                    this.form.status = response.data.status;
                    this.form.security = response.data.security;
                    this.form.mine_place = response.data.mine_place;
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
    },
};
</script>

<style lang="scss">
@import "@vueform/multiselect/themes/default.css";
.c-file-padding {
    padding: 1rem 0.75rem !important;
}
.invalid-feedback {
    display: block !important;
}
.invalid-bg {
    border-color: #f8d4d4 !important;
    background-color: #f8d4d4 !important;
}
</style>
