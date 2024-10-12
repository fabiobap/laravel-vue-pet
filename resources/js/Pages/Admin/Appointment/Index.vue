<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import {onMounted, ref, watch} from "vue";

const props = defineProps({
    appointments: {
        type: Object,
        default: () => [],
    },
    flash: String
});

const headers = [
    {title: 'ID', key: 'id', align: 'start'},
    {title: 'Time', key: 'appointment_time', align: 'start'},
    {title: 'Date', key: 'appointment_date', align: 'start'},
    {title: 'Animal', key: 'animal.name', align: 'start', sortable:false},
    {title: 'Species', key: 'animal.species', align: 'start'},
    {title: 'Client', key: 'animal.owner.name', align: 'start', sortable:false},
    {title: 'Vet', key: 'veterinary.name', align: 'start', sortable:false},
    {title: 'View', key: 'view', sortable: false},
    {title: 'Edit', key: 'edit', sortable: false},
    {title: 'Delete', key: 'delete', sortable: false},
]

const loading = ref(false)
const flashMessage = ref(props.flash)
const snackbar = ref(false)

const options = ref({
    page: props.appointments.meta.current_page,
    itemsPerPage: props.appointments.meta.per_page,
    sortBy: ['appointment_date'],
    sortDesc: ['asc']
});

const data = ref(props.appointments.data.data)

const editAppointment = (id) => {
    router.visit(route('appointments.edit', id));
};

const viewAppointment = (id) => {
    router.visit(route('appointments.show', id));
};

const loadItems = ({page, sortBy}) => {
    let params = {page: page, sortBy: sortBy[0]?.key ?? 'apppointment_date', orderBy: sortBy[0]?.order ?? 'asc'}
    router.get(
        '/appointments',
        params,
        {
            preserveState: true,
            only: ['appointments'],
            onSuccess: page => {
                data.value = page.props.appointments.data.data
                options.page = page.props.appointments.meta.current_page
            },
            onStart: visit => {
                loading.value = true
            },
            onFinish: visit => {
                loading.value = false
            },
        }
    )
}
watch(options, loadItems);

const dialog = ref(false);
const appointmentIdToDelete = ref(null);
const confirmDelete = () => {
    router.delete(route('appointments.destroy', appointmentIdToDelete.value), {
        onStart: visit => {
            loading.value = true
        },
        onFinish: visit => {
            loading.value = false
            closeDialog()
        },
        onSuccess: page => {
            flashMessage.value = page.props.flash;
            data.value = page.props.appointments.data.data
            snackbar.value = true;
        },
    });
};

const openDialog = (id) => {
    appointmentIdToDelete.value = id;
    dialog.value = true;
};

const closeDialog = () => {
    dialog.value = false;
    appointmentIdToDelete.value = null;
};

onMounted(() => {
    if (flashMessage.value) {
        snackbar.value = true;
    }
});

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
                            <v-data-table-server
                                v-model:items-per-page="appointments.meta.per_page"
                                :headers="headers"
                                :items="data"
                                :items-length="appointments.meta.total"
                                :options.sync="options"
                                loading-text="Loading... Please wait"
                                @update:options="loadItems"
                                :loading="loading"
                            >
                                <template v-slot:item.veterinary.name="{ item }">
                                    {{ item.veterinary?.name ?? '(empty)' }}
                                </template>
                                <template v-slot:item.edit="{ item }">
                                    <v-btn size="small" icon="mdi-pencil" color="indigo-darken-1" variant="tonal"
                                           @click="editAppointment(item.id)">
                                    </v-btn>
                                </template>
                                <template v-slot:item.view="{ item }">
                                    <v-btn size="small" icon="mdi-eye" color="blue-lighten-1" variant="tonal"
                                           @click="viewAppointment(item.id)">
                                    </v-btn>
                                </template>
                                <template v-slot:item.delete="{ item }">
                                    <v-btn size="small" icon="mdi-delete" color="red-accent-4" variant="tonal"
                                           @click="openDialog(item.id)">
                                    </v-btn>
                                </template>
                            </v-data-table-server>
                        </v-container>
                    </div>
                </div>
            </div>
        </div>
        <v-snackbar v-model="snackbar" color="success" :timeout="4000" elevation="24" top>
            {{ flashMessage }}
        </v-snackbar>
        <v-dialog v-model="dialog" max-width="400">
            <v-card>
                <v-card-title class="headline">Confirm Deletion</v-card-title>
                <v-card-text>Are you sure you want to delete this appointment?</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" @click="closeDialog">Cancel</v-btn>
                    <v-btn color="red darken-1" @click="confirmDelete">Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </AuthenticatedLayout>
</template>
