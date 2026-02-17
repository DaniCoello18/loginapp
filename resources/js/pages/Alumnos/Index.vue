<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { ref } from 'vue'
import { type BreadcrumbItem } from '@/types'

interface Alumno {
    id: number
    cedula: string
    nombre: string
    apellido: string
    fecha_nacimiento: string
    direccion: string
    correo: string
}

const props = defineProps<{
    alumnos: Alumno[]
    filters?: { search?: string }
}>()

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Alumnos', href: '/alumnos' }
]

// FORMULARIO CREAR
const form = useForm({
    cedula: '',
    nombre: '',
    apellido: '',
    fecha_nacimiento: '',
    direccion: '',
    correo: ''
})

// FORMULARIO EDITAR
const editForm = useForm({
    id: 0,
    cedula: '',
    nombre: '',
    apellido: '',
    fecha_nacimiento: '',
    direccion: '',
    correo: ''
})

const showEditModal = ref(false)

const submit = () => {
    form.post('/alumnos', {
        onSuccess: () => form.reset()
    })
}

const startEdit = (alumno: Alumno) => {
    editForm.id = alumno.id
    editForm.cedula = alumno.cedula
    editForm.nombre = alumno.nombre
    editForm.apellido = alumno.apellido
    editForm.fecha_nacimiento = alumno.fecha_nacimiento
    editForm.direccion = alumno.direccion
    editForm.correo = alumno.correo
    showEditModal.value = true
}

const update = () => {
    editForm.put(`/alumnos/${editForm.id}`, {
        onSuccess: () => {
            showEditModal.value = false
            editForm.reset()
        }
    })
}

const eliminar = (id: number) => {
    if (confirm('¿Eliminar alumno?')) {
        form.delete(`/alumnos/${id}`)
    }
}

// BÚSQUEDA
const search = useForm({
    search: props.filters?.search ?? ''
})

const buscar = () => {
    search.get('/alumnos/buscar', {
        preserveState: true,
        replace: true
    })
}

</script>

<template>
    <Head title="Alumnos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-8">

            <!-- FORMULARIO CREAR -->
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg font-bold mb-4">Nuevo Alumno</h2>
                <form @submit.prevent="submit" class="grid grid-cols-2 gap-4">
                    <input v-model="form.cedula" placeholder="Cédula" class="border p-2 rounded" />
                    <input v-model="form.nombre" placeholder="Nombre" class="border p-2 rounded" />
                    <input v-model="form.apellido" placeholder="Apellido" class="border p-2 rounded" />
                    <input type="date" v-model="form.fecha_nacimiento" class="border p-2 rounded" />
                    <input v-model="form.direccion" placeholder="Dirección" class="border p-2 rounded" />
                    <input v-model="form.correo" placeholder="Correo" class="border p-2 rounded" />
                    <button type="submit" class="col-span-2 bg-blue-500 text-white p-2 rounded hover:bg-blue-600" :disabled="form.processing">
                        Guardar
                    </button>
                </form>

                <div v-if="form.errors" class="text-red-500 mt-4">
                    <div v-for="(error, key) in form.errors" :key="key">{{ error }}</div>
                </div>
            </div>

            <!-- BÚSQUEDA -->
            <div class="mb-4">
                <input
                    v-model="search.search"
                    @input="buscar"
                    placeholder="Buscar alumno..."
                    class="border p-2 rounded w-full"
                />
            </div>

            <!-- LISTADO -->
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg font-bold mb-4">Lista de Alumnos</h2>
                <table class="w-full border">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2 border">Cédula</th>
                            <th class="p-2 border">Nombre</th>
                            <th class="p-2 border">Apellido</th>
                            <th class="p-2 border">Correo</th>
                            <th class="p-2 border">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="al in props.alumnos" :key="al.id">
                            <td class="border p-2">{{ al.cedula }}</td>
                            <td class="border p-2">{{ al.nombre }}</td>
                            <td class="border p-2">{{ al.apellido }}</td>
                            <td class="border p-2">{{ al.correo }}</td>
                            <td class="border p-2 space-x-2">
                                <button @click="startEdit(al)" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                    Editar
                                </button>
                                <button @click="eliminar(al.id)" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- MODAL EDITAR -->
            <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded shadow w-1/2">
                    <h2 class="text-lg font-bold mb-4">Editar Alumno</h2>
                    <form @submit.prevent="update" class="grid grid-cols-2 gap-4">
                        <input v-model="editForm.cedula" placeholder="Cédula" class="border p-2 rounded" />
                        <input v-model="editForm.nombre" placeholder="Nombre" class="border p-2 rounded" />
                        <input v-model="editForm.apellido" placeholder="Apellido" class="border p-2 rounded" />
                        <input type="date" v-model="editForm.fecha_nacimiento" class="border p-2 rounded" />
                        <input v-model="editForm.direccion" placeholder="Dirección" class="border p-2 rounded" />
                        <input v-model="editForm.correo" placeholder="Correo" class="border p-2 rounded" />
                        <button type="submit" class="col-span-2 bg-green-500 text-white p-2 rounded hover:bg-green-600" :disabled="editForm.processing">
                            Actualizar
                        </button>
                        <button type="button" @click="showEditModal = false" class="col-span-2 mt-2 bg-gray-500 text-white p-2 rounded hover:bg-gray-600">
                            Cancelar
                        </button>
                    </form>

                    <div v-if="editForm.errors" class="text-red-500 mt-4">
                        <div v-for="(error, key) in editForm.errors" :key="key">{{ error }}</div>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>
