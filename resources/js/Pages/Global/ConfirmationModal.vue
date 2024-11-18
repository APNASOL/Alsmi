<template>
    <button
        v-if="recordId"
        class="btn btn-danger btn-sm fs-6"
        type="button"
        data-bs-toggle="modal"
        :data-bs-target="'#confirmationModal-' + recordId"
        title="Delete"
    >
        <i class="bi bi-check-circle text-white me-1"></i>
        Close Now
    </button>

    <!-- delete Modal -->
    <div class="modal fade" :id="'confirmationModal-' + recordId" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="block-content text-center p-4">
                        <h2>Are you sure?</h2>

                        <button
                            v-if="recordId"
                            type="button"
                            class="btn btn-sm btn-success me-2"
                            data-bs-dismiss="modal"
                            @click="$emit('processThis', recordId)"
                        >
                            Yes, Process it!
                        </button>

                        <button
                            type="button"
                            class="btn btn-sm btn-dark"
                            data-bs-dismiss="modal"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["recordId"],
    emits: ["processThis"],
};
</script>
