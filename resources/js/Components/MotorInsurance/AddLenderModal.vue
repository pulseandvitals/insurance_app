<script setup>
import { useForm } from '@inertiajs/vue3';
import { X, Landmark } from 'lucide-vue-next';
import Modal from '@/Components/UI/Modal.vue';
import SearchableSelect from '@/Components/UI/SearchableSelect.vue';
import Button from '@/Components/UI/Button.vue';
import { lenders } from '@/Data/lenderData';

const props = defineProps({
    show: Boolean,
    motorQuoteId: [Number, String],
});
const emit = defineEmits(['close']);

const form = useForm({
    type: 'lender',
    lender: '',
});

function submit() {
    form.post(route('motor-risks.policyholders.store', props.motorQuoteId), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            emit('close');
        },
    });
}
</script>

<template>
    <Modal :show="show" title="Add Lender" @close="$emit('close')">
        <SearchableSelect
            id="lender"
            v-model="form.lender"
            label="Select an Option"
            required
            :error="form.errors.lender"
            :options="lenders"
            placeholder="Search leasing company or mortgagee..."
        />

        <template #footer>
            <Button variant="secondary" @click="$emit('close')"><X class="h-4 w-4" />Close</Button>
            <Button :disabled="form.processing" @click="submit"><Landmark class="h-4 w-4" />Add Policyholder</Button>
        </template>
    </Modal>
</template>
