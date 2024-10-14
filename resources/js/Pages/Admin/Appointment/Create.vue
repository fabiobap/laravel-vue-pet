<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import {ref} from "vue";

const props = defineProps({
    veterinaries: {
        type: Array,
        default: () => [],
    },
});

const loading = ref(false)

const form = useForm({
    client: {
        name: null,
        email: null,
    },
    animal: {
        name: null,
        birthdate: null,
        species: null,
    },
    appointment: {
        appointment_date: null,
        appointment_time: null,
        symptoms: null,
        user_id: null,
    }
});

const errors = ref({client: {}, animal: {}, appointment: {}});
const menuTimer = ref(false);
const menuDateAppointment = ref(false);
const menuBirthdate = ref(false);

const submit = () => {
    form.post(route('appointments.store'), {
        onError: (error) => {
            errors.value = error;
        },
        onStart: visit => {
            loading.value = true
        },
        onFinish: visit => {
            loading.value = false
        },
    });
};

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
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        <v-container>
                            <v-card>
                                <v-card-title>
                                    Create Appointment
                                </v-card-title>
                                <v-card-text>
                                    <v-form @submit.prevent="submit">
                                        <v-container>
                                            <v-row>
                                                <v-col
                                                    cols="12"
                                                    md="4"
                                                >
                                                    <v-text-field
                                                        v-model="form.client.name"
                                                        label="Tutor's name"
                                                        :error-messages="errors['client.name']"
                                                        required
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                    md="4"
                                                >
                                                    <v-text-field
                                                        v-model="form.client.email"
                                                        label="Tutor's email"
                                                        :error-messages="errors['client.email']"
                                                        required
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                    md="4"
                                                >
                                                    <v-select
                                                        :disabled="!$page.props.auth.can.attach_appointment"
                                                        v-model="form.appointment.user_id"
                                                        :items="veterinaries"
                                                        item-title="name"
                                                        item-value="id"
                                                        label="Veterinary"
                                                        variant="underlined"
                                                        :error-messages="errors['appointment.user_id']"
                                                        required
                                                    ></v-select>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                    md="4"
                                                >
                                                    <v-text-field
                                                        v-model="form.animal.name"
                                                        label="Pet's name"
                                                        :error-messages="errors['animal.name']"
                                                        required
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                    md="4"
                                                >
                                                    <v-text-field
                                                        v-model="form.animal.species"
                                                        label="Pet's species"
                                                        :error-messages="errors['animal.species']"
                                                        required
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                    md="4"
                                                >
                                                    <v-text-field
                                                        variant="underlined"
                                                        label="Pet's birthday"
                                                        v-model="form.animal.birthdate"
                                                        :active="menuBirthdate"
                                                        prepend-icon="$calendar"
                                                        readonly
                                                        :error-messages="errors['animal.birthdate']"
                                                    >
                                                        <v-menu
                                                            v-model="menuBirthdate"
                                                            :close-on-content-click="false"
                                                            activator="parent"
                                                            transition="scale-transition"
                                                        >
                                                            <v-date-picker
                                                                v-if="menuBirthdate"
                                                                v-model="form.animal.birthdate"/>
                                                        </v-menu>
                                                    </v-text-field>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                    md="6"
                                                >
                                                    <v-text-field
                                                        variant="underlined"
                                                        label="Appointment Date"
                                                        v-model="form.appointment.appointment_date"
                                                        :active="menuDateAppointment"
                                                        prepend-icon="$calendar"
                                                        readonly
                                                        :error-messages="errors['appointment.appointment_date']"
                                                    >
                                                        <v-menu
                                                            v-model="menuDateAppointment"
                                                            :close-on-content-click="false"
                                                            activator="parent"
                                                            transition="scale-transition"
                                                        >
                                                            <v-date-picker
                                                                v-if="menuDateAppointment"
                                                                v-model="form.appointment.appointment_date"/>
                                                        </v-menu>
                                                    </v-text-field>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                    md="6"
                                                >
                                                    <v-text-field
                                                        variant="underlined"
                                                        v-model="form.appointment.appointment_time"
                                                        :active="menuTimer"
                                                        label="Appointment Time"
                                                        prepend-icon="mdi-clock-time-four-outline"
                                                        readonly
                                                        :error-messages="errors['appointment.appointment_time']"
                                                    >
                                                        <v-menu
                                                            v-model="menuTimer"
                                                            :close-on-content-click="false"
                                                            activator="parent"
                                                            transition="scale-transition"
                                                        >
                                                            <v-time-picker
                                                                v-if="menuTimer"
                                                                v-model="form.appointment.appointment_time"
                                                                full-width
                                                                format="24hr"
                                                            ></v-time-picker>
                                                        </v-menu>
                                                    </v-text-field>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                    md="12"
                                                >
                                                    <v-text-field
                                                        v-model="form.appointment.symptoms"
                                                        label="Symptoms"
                                                        :error-messages="errors['appointment.symptoms']"
                                                        required
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col cols="auto">
                                                    <v-btn :loading="loading" type="submit" color="primary">
                                                        Send
                                                    </v-btn>
                                                </v-col>
                                                <v-col cols="auto">
                                                    <v-btn :loading="loading"  @click="goBack" color="grey-darken-1">Go Back</v-btn>
                                                </v-col>
                                            </v-row>
                                        </v-container>
                                    </v-form>
                                </v-card-text>
                            </v-card>
                        </v-container>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
