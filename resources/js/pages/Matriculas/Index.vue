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
}

interface Materia {
    id: number
    codigo: string
    nombre: string
}

interface Matricula {
    id: number
    codigo: string
    fecha_matricula: string
    alumno_id: number
    materia_id: number
    alumno: Alumno
    materia: Materia
}

const props = defineProps<{
    matriculas: Matricula[]
    alumnos: Alumno[]
    materias: Materia[]
    filters?: { search?: string }
}>()

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Matrículas', href: '/matriculas' }
]

// FORMULARIO CREAR
const form = useForm({
    codigo: '',
    fecha_matricula: '',
    alumno_id: 0,
    materia_id: 0
})

// FORMULARIO EDITAR
const editForm = useForm({
    id: 0,
    codigo: '',
    fecha_matricula: '',
    alumno_id: 0,
    materia_id: 0
})

const showEditModal = ref(false)

const submit = () => {
    form.post('/matriculas', {
        onSuccess: () => form.reset()
    })
}

const startEdit = (matricula: Matricula) => {
    editForm.id = matricula.id
    editForm.codigo = matricula.codigo
    editForm.fecha_matricula = matricula.fecha_matricula
    editForm.alumno_id = matricula.alumno_id
    editForm.materia_id = matricula.materia_id
    showEditModal.value = true
}

const update = () => {
    editForm.put(`/matriculas/${editForm.id}`, {
        onSuccess: () => {
            showEditModal.value = false
            editForm.reset()
        }
    })
}

const eliminar = (id: number) => {
    if (confirm('¿Eliminar matrícula?')) {
        form.delete(`/matriculas/${id}`)
    }
}

// BÚSQUEDA
const search = useForm({
    search: props.filters?.search ?? ''
})

const buscar = () => {
    search.get('/matriculas/buscar', {
        preserveState: true,
        replace: true
    })
}

</script>

<template>
    <Head title="Matrículas" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-8">

            <!-- FORMULARIO CREAR -->
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg font-bold mb-4">Nueva Matrícula</h2>
                <form @submit.prevent="submit" class="grid grid-cols-2 gap-4">
                    <input v-model="form.codigo" placeholder="Código (MAT-001)" class="border p-2 rounded" />
                    <input type="date" v-model="form.fecha_matricula" class="border p-2 rounded" />
                    <select v-model.number="form.alumno_id" class="border p-2 rounded">
                        <option value="0">Seleccionar Alumno</option>
                        <option v-for="alu in props.alumnos" :key="alu.id" :value="alu.id">
                            {{ alu.cedula }} - {{ alu.nombre }} {{ alu.apellido }}
                        </option>
                    </select>
                    <select v-model.number="form.materia_id" class="border p-2 rounded">
                        <option value="0">Seleccionar Materia</option>
                        <option v-for="mat in props.materias" :key="mat.id" :value="mat.id">
                            {{ mat.codigo }} - {{ mat.nombre }}
                        </option>
                    </select>
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
                    placeholder="Buscar matrícula..."
                    class="border p-2 rounded w-full"
                />
            </div>

            <!-- LISTADO -->
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg font-bold mb-4">Lista de Matrículas</h2>
                <table class="w-full border">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2 border">Código</th>
                            <th class="p-2 border">Alumno</th>
                            <th class="p-2 border">Materia</th>
                            <th class="p-2 border">Fecha Matrícula</th>
                            <th class="p-2 border">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="mat in props.matriculas" :key="mat.id">
                            <td class="border p-2">{{ mat.codigo }}</td>
                            <td class="border p-2">{{ mat.alumno.nombre }} {{ mat.alumno.apellido }}</td>
                            <td class="border p-2">{{ mat.materia.nombre }}</td>
                            <td class="border p-2">{{ mat.fecha_matricula }}</td>
                            <td class="border p-2 space-x-2">
                                <button @click="startEdit(mat)" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                    Editar
                                </button>
                                <button @click="eliminar(mat.id)" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
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
                    <h2 class="text-lg font-bold mb-4">Editar Matrícula</h2>
                    <form @submit.prevent="update" class="grid grid-cols-2 gap-4">
                        <input v-model="editForm.codigo" placeholder="Código" class="border p-2 rounded" />
                        <input type="date" v-model="editForm.fecha_matricula" class="border p-2 rounded" />
                        <select v-model.number="editForm.alumno_id" class="border p-2 rounded col-span-2">
                            <option value="0">Seleccionar Alumno</option>
                            <option v-for="alu in props.alumnos" :key="alu.id" :value="alu.id">
                                {{ alu.cedula }} - {{ alu.nombre }} {{ alu.apellido }}
                            </option>
                        </select>
                        <select v-model.number="editForm.materia_id" class="border p-2 rounded col-span-2">
                            <option value="0">Seleccionar Materia</option>
                            <option v-for="mat in props.materias" :key="mat.id" :value="mat.id">
                                {{ mat.codigo }} - {{ mat.nombre }}
                            </option>
                        </select>
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
