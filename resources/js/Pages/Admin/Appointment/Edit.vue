<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import {ref} from "vue";
import moment from "moment";

const props = defineProps({
    appointment: {
        type: Object
    },
    veterinaries: {
        type: Array,
        default: () => [],
    },
});

const loading = ref(false)
console.log(props.appointment)

const form = useForm({
    appointment_date: moment(props.appointment.appointment_date_raw).toDate(),
    appointment_time: props.appointment.appointment_time_raw,
    symptoms: props.appointment.symptoms,
    user_id: props.appointment.veterinary?.id,
    animal_id: props.appointment.animal.id,
});

const errors = ref({});
const menuTimer = ref(false);
const menuDate = ref(false);

const submit = () => {
    form.put(route('appointments.update', props.appointment.id), {
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
                                    Edit User
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
                                                        v-model="appointment.animal.name"
                                                        label="Patient"
                                                        readonly
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                    md="4"
                                                >
                                                    <v-text-field
                                                        v-model="form.symptoms"
                                                        label="Symptoms"
                                                        :error-messages="errors.symptoms"
                                                        required
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                    md="4"
                                                >
                                                    <v-select
                                                        v-model="form.user_id"
                                                        :items="veterinaries"
                                                        item-title="name"
                                                        item-value="id"
                                                        label="Veterinary"
                                                        variant="underlined"
                                                        :error-messages="errors.user_id"
                                                        required
                                                    ></v-select>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                    md="6"
                                                >
                                                    <v-text-field
                                                        variant="underlined"
                                                        label="Appointment Date"
                                                        v-model="form.appointment_date"
                                                        :active="menuDate"
                                                        prepend-icon="$calendar"
                                                        readonly
                                                        :error-messages="errors.appointment_date"
                                                    >
                                                        <v-menu
                                                            v-model="menuDate"
                                                            :close-on-content-click="false"
                                                            activator="parent"
                                                            transition="scale-transition"
                                                        >
                                                            <v-date-picker
                                                                v-if="menuDate"
                                                                v-model="form.appointment_date"/>
                                                        </v-menu>
                                                    </v-text-field>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                    md="6"
                                                >
                                                    <v-text-field
                                                        variant="underlined"
                                                        v-model="form.appointment_time"
                                                        :active="menuTimer"
                                                        label="Appointment time"
                                                        prepend-icon="mdi-clock-time-four-outline"
                                                        readonly
                                                        :error-messages="errors.appointment_time"
                                                    >
                                                        <v-menu
                                                            v-model="menuTimer"
                                                            :close-on-content-click="false"
                                                            activator="parent"
                                                            transition="scale-transition"
                                                        >
                                                            <v-time-picker
                                                                v-if="menuTimer"
                                                                v-model="form.appointment_time"
                                                                full-width
                                                                format="24hr"
                                                            ></v-time-picker>
                                                        </v-menu>
                                                    </v-text-field>
                                                </v-col>
                                                <v-btn :loading="loading" type="submit" color="primary">Update</v-btn>
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
