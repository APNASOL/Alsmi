<template>
    <button
        v-if="record_id"
        class="btn btn-sm fs-6"
        title="Edit"
        data-bs-toggle="modal"
        data-bs-target="#updateRecordModal"
    >
        <i class="bi bi-pencil"></i>
    </button>

    <button
        v-else
        class="btn btn-success mt-3"
        data-bs-toggle="modal"
        data-bs-target="#updateRecordModal"
        @click="clearFields"
    >
        <i class="bi bi-plus-lg"></i> New {{ process }}
    </button>

    <!-- Transaction Modal -->
    <div
        class="modal fade"
        id="updateRecordModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    {{ record_id }}
                    {{ record_id }}-{{ form.id }}
                    <h5 class="modal-title text-primary" v-if="record_id">
                        {{ form.name }}
                    </h5>
                    <h5 class="modal-title text-primary" v-else>
                        New {{ process }} Entry
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <div class="card card-body p-3">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="name" class="form-label"
                                    >{{ process }} name</label
                                >
                                <input
                                    type="text"
                                    class="form-control"
                                    id="name"
                                    v-model="form.name"
                                    :class="{
                                        'invalid-bg': formErrors.name,
                                    }"
                                />
                                <div
                                    v-if="formErrors.name"
                                    class="invalid-feedback"
                                >
                                    {{ formErrors.name[0] }}
                                </div>
                            </div>

                            <div class="mt-3">
                                <button
                                    type="submit"
                                    class="btn btn-success"
                                    v-if="formStatus === 1"
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
                                    ></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button
            hidden
            data-bs-toggle="modal"
            data-bs-target="#customermodal"
            ref="closeModal"
        ></button>
    </div>
</template>

<script>
import axios from "axios";
export default {
    props: ["process", "record_id"],
    emits: ["save-success"], // Declare custom events

    crated() {
        console.log("commming".this.process);
        console.log("commming".this.record_id);
        if (this.record_id) {
        }
    },
    data() {
        return {
            transactionEntries: [],
            form: {
                id: "",
                name: "",
                process: this.process,
            },
            formErrors: [],
            formStatus: 1, // 1 = ready, 0 = saving
        };
    },

    methods: {
        submit() {
            this.formStatus = 0;

            axios
                .post(route("api.income.expanse.store"), this.form)
                .then(() => {
                    this.formStatus = 1;
                    toastr.success("Record saved successfully.");
                    this.$emit("save-success");
                    this.$refs.closeModal.click();
                })
                .catch((error) => {
                    this.formStatus = 1;
                    this.formErrors = error.response.data.errors || {};
                });
        },
        showEntry(entry_id) {
            console.log(entry_id);
            axios
                .get(route("api.income.expanse.show", [entry_id, this.process]))
                .then((response) => {
                    this.form.id = response.data.id;
                    this.form.name = response.data.name;
                    console.log(response.data);
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        clearFields() {
            this.form.name = "";
        },
    },
};
</script>
