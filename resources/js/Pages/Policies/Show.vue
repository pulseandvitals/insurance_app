<script setup>
import { ref } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import {
    Printer,
    FileText,
    Download,
    ShieldCheck,
    BookText,
    Upload,
    Eye,
    X,
} from "lucide-vue-next";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Card from "@/Components/UI/Card.vue";
import Badge from "@/Components/UI/Badge.vue";
import Button from "@/Components/UI/Button.vue";
import Banner from "@/Components/UI/Banner.vue";
import Modal from "@/Components/UI/Modal.vue";
import Input from "@/Components/UI/Input.vue";

const props = defineProps({
    policy: Object,
});

const showUploadCov = ref(false);
const covForm = useForm({ cov_document: null });

function submitCovUpload() {
    covForm.post(route("policies.cov.upload", props.policy.id), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            showUploadCov.value = false;
            covForm.reset();
        },
    });
}

function closeCovUpload() {
    showUploadCov.value = false;
    covForm.reset();
    covForm.clearErrors();
}

function viewCov() {
    window.open(route("policies.cov.view", props.policy.id), "_blank");
}

function peso(value) {
    return (
        "₱" +
        Number(value).toLocaleString("en-PH", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        })
    );
}

function printMode(mode) {
    window.open(route("policies.print", [props.policy.id, mode]), "_blank");
}

const wordings = [
    "Section I - Liability",
    "Section II - No Fault Indemnity",
    "General Exceptions",
    "Definitions",
    "Conditions",
    "Nuclear Exclusions",
    "Waiver Clause",
    "Short Period Rate Scale",
];
</script>

<template>
    <Head :title="`Policy ${policy.online_policy_no}`" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-4xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <div>
                <h2
                    class="text-xl font-semibold text-gray-900 dark:text-gray-100"
                >
                    Policy Contract
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Online Policy No. {{ policy.online_policy_no }} · Motor
                    Vehicle Insurance · {{ policy.producer.first_name }}
                    {{ policy.producer.last_name }}
                </p>
            </div>

            <Banner variant="good"
                >Posted to Genweb: {{ policy.genweb_code }}</Banner
            >

            <!-- Section 1 -->
            <Card title="Policy Schedule">
                <div class="grid grid-cols-2 gap-4 text-sm sm:grid-cols-3">
                    <div>
                        <p class="text-xs uppercase text-gray-400">
                            Issued Date
                        </p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">
                            {{ new Date(policy.issued_at).toLocaleString() }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-400">
                            Contract Term From
                        </p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">
                            {{
                                new Date(
                                    policy.contract_from,
                                ).toLocaleDateString()
                            }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-400">
                            Contract Term To
                        </p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">
                            {{
                                new Date(
                                    policy.contract_to,
                                ).toLocaleDateString()
                            }}
                        </p>
                    </div>
                    <div class="col-span-2 sm:col-span-3">
                        <p class="text-xs uppercase text-gray-400">Insured/s</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">
                            {{
                                policy.motor_quote.policyholders
                                    .map((p) => p.name)
                                    .join(", ") || "—"
                            }}
                        </p>
                    </div>
                </div>

                <div
                    class="mt-4 w-full max-w-xs rounded-lg border border-gray-200 p-4 dark:border-gray-700"
                >
                    <p
                        class="mb-2 text-xs font-semibold uppercase text-gray-400"
                    >
                        Premium Due
                    </p>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Net Premium</span
                            ><span
                                class="font-medium text-gray-900 dark:text-gray-100"
                                >{{
                                    peso(policy.motor_quote.net_premium)
                                }}</span
                            >
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Taxes & Charges</span
                            ><span
                                class="font-medium text-gray-900 dark:text-gray-100"
                                >{{
                                    peso(
                                        policy.motor_quote.total_premium -
                                            policy.motor_quote.net_premium,
                                    )
                                }}</span
                            >
                        </div>
                        <div
                            class="flex justify-between border-t border-gray-100 pt-1 font-bold text-gray-900 dark:border-gray-700 dark:text-gray-100"
                        >
                            <span>Total Premium</span
                            ><span>{{
                                peso(policy.motor_quote.total_premium)
                            }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex gap-3">
                    <Button
                        variant="secondary"
                        size="sm"
                        @click="printMode('schedule')"
                        ><Printer class="h-3.5 w-3.5" />Print Schedule</Button
                    >
                    <Button
                        variant="secondary"
                        size="sm"
                        @click="printMode('premium-statement')"
                        ><FileText class="h-3.5 w-3.5" />Premium
                        Statement</Button
                    >
                </div>
            </Card>

            <!-- Section 2 -->
            <Card title="Certificate/s of Cover & Validation">
                <div class="grid grid-cols-2 gap-4 text-sm sm:grid-cols-3">
                    <div>
                        <p class="text-xs uppercase text-gray-400">
                            Risk/s Insured
                        </p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">
                            1
                        </p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-400">Vehicle</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">
                            {{ policy.motor_quote.vehicle_title }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-400">Plate No.</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">
                            {{ policy.motor_quote.plate_no }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-400">COC No.</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">
                            {{ policy.coc_no }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-400">
                            Authentication No.
                        </p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">
                            {{ policy.authentication_no }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-400">
                            Registration Type
                        </p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">
                            {{ policy.motor_quote.lto_registration_type }}
                        </p>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap items-center gap-3">
                    <Button
                        variant="secondary"
                        size="sm"
                        @click="printMode('coc')"
                        ><Printer class="h-3.5 w-3.5" />Print COC</Button
                    >
                    <a :href="route('policies.download', policy.id)"
                        ><Button variant="outline" size="sm"
                            ><Download class="h-3.5 w-3.5" />Download
                            PDF</Button
                        ></a
                    >
                    <Button
                        variant="secondary"
                        size="sm"
                        @click="printMode('cov')"
                        ><ShieldCheck class="h-3.5 w-3.5" />Print COV</Button
                    >
                    <Button
                        variant="outline"
                        size="sm"
                        @click="showUploadCov = true"
                        ><Upload class="h-3.5 w-3.5" />{{
                            policy.has_cov_document
                                ? "Replace COV"
                                : "Upload COV"
                        }}</Button
                    >
                    <template v-if="policy.has_cov_document">
                        <Button
                            variant="secondary"
                            size="sm"
                            @click="viewCov"
                            ><Eye class="h-3.5 w-3.5" />View COV</Button
                        >
                        <Badge variant="good">COV Uploaded</Badge>
                    </template>
                </div>
            </Card>

            <!-- Section 3 -->
            <Card title="Policy Wordings">
                <div class="flex items-center justify-between">
                    <Badge variant="primary"
                        >Motor Vehicle Insurance —
                        {{ policy.motor_quote.vehicle_class }}</Badge
                    >
                </div>
                <ul
                    class="mt-4 list-disc space-y-1.5 pl-5 text-sm text-gray-600 dark:text-gray-300"
                >
                    <li v-for="w in wordings" :key="w">{{ w }}</li>
                </ul>

                <div class="mt-4">
                    <Button
                        variant="secondary"
                        size="sm"
                        @click="printMode('jacket')"
                        ><BookText class="h-3.5 w-3.5" />Policy Jacket</Button
                    >
                </div>
            </Card>
        </div>

        <Modal
            :show="showUploadCov"
            title="Upload COV"
            @close="closeCovUpload"
        >
            <div class="space-y-4">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Upload the Certificate of Validation (COV) document for
                    policy
                    <span class="font-semibold">{{
                        policy.online_policy_no
                    }}</span
                    >. Accepted formats: JPG, PNG, or PDF, up to 5MB.
                </p>
                <Input
                    id="cov_document"
                    type="file"
                    label="COV Document"
                    required
                    :error="covForm.errors.cov_document"
                    @change="(e) => (covForm.cov_document = e.target.files[0])"
                />
            </div>
            <template #footer>
                <Button variant="secondary" @click="closeCovUpload"
                    ><X class="h-4 w-4" />Close</Button
                >
                <Button
                    :disabled="covForm.processing"
                    @click="submitCovUpload"
                    ><Upload class="h-4 w-4" />Upload</Button
                >
            </template>
        </Modal>
    </AuthenticatedLayout>
</template>
