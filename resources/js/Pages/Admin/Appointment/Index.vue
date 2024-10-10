<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';

defineProps({
    posts: {
        type: Array,
        default: () => [],
    },
});

const headers = ["appointments", "body"];
</script>

<template>
    <Head title="Appointments"/>
    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Appointments
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        <table>
                            <thead>
                            <tr>
                                <th v-for="header in headers" :key="header">
                                    {{ header }}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="appointment in appointments" :key="appointment.id">
                                <td>{{ appointment.title }}</td>
                                <td>{{ appointment.body }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
