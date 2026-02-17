<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'

// Interfaces
interface Profesor {
    id: number
    cedula: string
    nombre: string
    apellido: string
    correo: string
    especialidad_id: number
}

interface Especialidad {
    id: number
    codigo: string
    nombre: string
}

// Props
const props = defineProps<{
    profesores: Profesor[]
    especialidades: Especialidad[]
    filters?: { search?: string }
}>()

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Profesores', href: '/profesores' }
]

// FORMULARIO CREAR
const form = useForm({
    cedula: '',
    nombre: '',
    apellido: '',
    correo: '',
    especialidad_id: 0 // número para campo especialidad
})

// FORMULARIO EDITAR
const editForm = useForm({
    id: 0,
    cedula: '',
    nombre: '',
    apellido: '',
    correo: '',
    especialidad_id: 0 // número para campo especialidad
})

const showEditModal = ref(false)

// FUNCIONES CRUD
const submit = () => {
    form.post('/profesores', { onSuccess: () => form.reset() })
}

const startEdit = (profesor: Profesor) => {
    editForm.id = profesor.id
    editForm.cedula = profesor.cedula
    editForm.nombre = profesor.nombre
    editForm.apellido = profesor.apellido
    editForm.correo = profesor.correo
    editForm.especialidad_id = profesor.especialidad_id
    showEditModal.value = true
}

const update = () => {
    editForm.put(`/profesores/${editForm.id}`, {
        onSuccess: () => {
            showEditModal.value = false
            editForm.reset()
        }
    })
}

const eliminar = (id: number) => {
    if (confirm('¿Eliminar Profesor?')) {
        form.delete(`/profesores/${id}`)
    }
}

// BÚSQUEDA
const search = useForm({
    search: props.filters?.search ?? ''
})

const buscar = () => {
    search.get('/profesores/buscar', { preserveState: true, replace: true })
}
</script>

<template>
<Head title="Profesores" />

<AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6 space-y-8">

        <!-- FORMULARIO CREAR -->
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-bold mb-4">Nuevo Profesor</h2>
            <form @submit.prevent="submit" class="grid grid-cols-2 gap-4">
                <input v-model="form.cedula" placeholder="Cedula" class="border p-2 rounded" />
                <input v-model="form.nombre" placeholder="Nombre" class="border p-2 rounded" />
                <input v-model="form.apellido" placeholder="Apellido" class="border p-2 rounded" />
                <input v-model="form.correo" placeholder="Correo" class="border p-2 rounded" />

                <!-- SELECT ESPECIALIDAD -->
                <select v-model.number="form.especialidad_id" class="border p-2 rounded">
                    <option :value="0">Selecciona una especialidad</option>
                    <option v-for="esp in props.especialidades" :key="esp.id" :value="esp.id">
                        {{ esp.nombre }}
                    </option>
                </select>

                <button type="submit" class="col-span-2 bg-blue-500 text-white p-2 rounded hover:bg-blue-600"
                    :disabled="form.processing">
                    Guardar
                </button>
            </form>

            <div v-if="form.errors" class="text-red-500 mt-4">
                <div v-for="(error, key) in form.errors" :key="key">{{ error }}</div>
            </div>
        </div>

        <!-- BÚSQUEDA -->
        <div class="mb-4">
            <input v-model="search.search" @input="buscar" placeholder="Buscar profesor..."
                class="border p-2 rounded w-full" />
        </div>

        <!-- LISTADO -->
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-bold mb-4">Lista de Profesores</h2>
            <table class="w-full border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2 border">Cedula</th>
                        <th class="p-2 border">Nombre</th>
                        <th class="p-2 border">Apellido</th>
                        <th class="p-2 border">Correo</th>
                        <th class="p-2 border">Especialidad</th>
                        <th class="p-2 border">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="al in props.profesores" :key="al.id">
                        <td class="border p-2">{{ al.cedula }}</td>
                        <td class="border p-2">{{ al.nombre }}</td>
                        <td class="border p-2">{{ al.apellido }}</td>
                        <td class="border p-2">{{ al.correo }}</td>
                        <td class="border p-2">
                            {{ props.especialidades.find(e => e.id === al.especialidad_id)?.nombre || '-' }}
                        </td>
                        <td class="border p-2 space-x-2">
                            <button @click="startEdit(al)"
                                class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                Editar
                            </button>
                            <button @click="eliminar(al.id)"
                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- MODAL EDITAR -->
        <div v-if="showEditModal"
            class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow w-1/2">
                <h2 class="text-lg font-bold mb-4">Editar Profesor</h2>
                <form @submit.prevent="update" class="grid grid-cols-2 gap-4">
                    <input v-model="editForm.cedula" placeholder="Cédula" class="border p-2 rounded" />
                    <input v-model="editForm.nombre" placeholder="Nombre" class="border p-2 rounded" />
                    <input v-model="editForm.apellido" placeholder="Apellido" class="border p-2 rounded" />
                    <input v-model="editForm.correo" placeholder="Correo" class="border p-2 rounded" />

                    <!-- SELECT ESPECIALIDAD -->
                    <select v-model.number="editForm.especialidad_id" class="border p-2 rounded">
                        <option :value="0">Selecciona una especialidad</option>
                        <option v-for="esp in props.especialidades" :key="esp.id" :value="esp.id">
                            {{ esp.nombre }}
                        </option>
                    </select>

                    <button type="submit"
                        class="col-span-2 bg-green-500 text-white p-2 rounded hover:bg-green-600"
                        :disabled="editForm.processing">
                        Actualizar
                    </button>
                    <button type="button" @click="showEditModal = false"
                        class="col-span-2 mt-2 bg-gray-500 text-white p-2 rounded hover:bg-gray-600">
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
