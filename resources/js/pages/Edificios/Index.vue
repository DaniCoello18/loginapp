<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { ref } from 'vue'
import { type BreadcrumbItem } from '@/types'

interface Edificio {
    id: number
    codigo: string
    nombre: string
    direccion: string
}

const props = defineProps<{
    edificios: Edificio[]
    filters?: { search?: string }
}>()

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Edificios', href: '/edificios' }
]

// FORMULARIO CREAR
const form = useForm({
    codigo: '',
    nombre: '',
    direccion: ''
})

// FORMULARIO EDITAR
const editForm = useForm({
    id: 0,
    codigo: '',
    nombre: '',
    direccion: ''
})

const showEditModal = ref(false)

const submit = () => {
    form.post('/edificios', {
        onSuccess: () => form.reset()
    })
}

const startEdit = (edificio: Edificio) => {
    editForm.id = edificio.id
    editForm.codigo = edificio.codigo
    editForm.nombre = edificio.nombre
    editForm.direccion = edificio.direccion
    showEditModal.value = true
}

const update = () => {
    editForm.put(`/edificios/${editForm.id}`, {
        onSuccess: () => {
            showEditModal.value = false
            editForm.reset()
        }
    })
}

const eliminar = (id: number) => {
    if (confirm('¿Eliminar edificio?')) {
        form.delete(`/edificios/${id}`)
    }
}

// BÚSQUEDA
const search = useForm({
    search: props.filters?.search ?? ''
})

const buscar = () => {
    search.get('/edificios/buscar', {
        preserveState: true,
        replace: true
    })
}

</script>

<template>
    <Head title="Edificios" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-8">

            <!-- FORMULARIO CREAR -->
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg font-bold mb-4">Nuevo Edificio</h2>
                <form @submit.prevent="submit" class="grid grid-cols-2 gap-4">
                    <input v-model="form.codigo" placeholder="Código (EDA-001)" class="border p-2 rounded" />
                    <input v-model="form.nombre" placeholder="Nombre" class="border p-2 rounded" />
                    <input v-model="form.direccion" placeholder="Dirección" class="border p-2 rounded col-span-2" />
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
                    placeholder="Buscar edificio..."
                    class="border p-2 rounded w-full"
                />
            </div>

            <!-- LISTADO -->
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg font-bold mb-4">Lista de Edificios</h2>
                <table class="w-full border">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2 border">Código</th>
                            <th class="p-2 border">Nombre</th>
                            <th class="p-2 border">Dirección</th>
                            <th class="p-2 border">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="ed in props.edificios" :key="ed.id">
                            <td class="border p-2">{{ ed.codigo }}</td>
                            <td class="border p-2">{{ ed.nombre }}</td>
                            <td class="border p-2">{{ ed.direccion }}</td>
                            <td class="border p-2 space-x-2">
                                <button @click="startEdit(ed)" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                    Editar
                                </button>
                                <button @click="eliminar(ed.id)" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
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
                    <h2 class="text-lg font-bold mb-4">Editar Edificio</h2>
                    <form @submit.prevent="update" class="grid grid-cols-2 gap-4">
                        <input v-model="editForm.codigo" placeholder="Código" class="border p-2 rounded" />
                        <input v-model="editForm.nombre" placeholder="Nombre" class="border p-2 rounded" />
                        <input v-model="editForm.direccion" placeholder="Dirección" class="border p-2 rounded col-span-2" />
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
