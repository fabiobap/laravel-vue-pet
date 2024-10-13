<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';

const props = defineProps({
    appointment: {
        type: Object
    },
    veterinaries: {
        type: Array,
        default: () => [],
    },
});

const goBack = () => {
    window.history.back();
};

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
                    <div class="p-6 text-gray-900">
                        <v-container>
                            <v-card>
                                <v-card-title>Appointment Details</v-card-title>
                                <v-card-text>
                                    <v-row>
                                        <v-col cols="12" md="6">
                                            <strong>Appointment Date:</strong> {{ appointment.appointment_date }}
                                        </v-col>
                                        <v-col cols="12" md="6">
                                            <strong>Appointment Time:</strong> {{ appointment.appointment_time }}
                                        </v-col>
                                        <v-col cols="12" md="6">
                                            <strong>Veterinary:</strong> {{ appointment.veterinary.name }}
                                        </v-col>
                                        <v-col cols="12" md="6">
                                            <strong>Patient:</strong> {{ appointment.animal.name }}
                                        </v-col>
                                        <v-col cols="12" md="6">
                                            <strong>Species:</strong> {{ appointment.animal.species }}
                                        </v-col>
                                        <v-col cols="12" md="6">
                                            <strong>Age:</strong> {{ appointment.animal.birthdate }}
                                        </v-col>
                                        <v-col cols="12" md="12">
                                            <strong>Symptoms:</strong> {{ appointment.symptoms }}
                                        </v-col>
                                    </v-row>
                                </v-card-text>
                                <v-card-actions>
                                    <v-btn @click="goBack" color="grey-darken-1">Go Back</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-container>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
