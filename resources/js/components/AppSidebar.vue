<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, GraduationCap, LayoutGrid, Building2, Clock, ClipboardList } from 'lucide-vue-next';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import AppLogo from './AppLogo.vue';



const mainNavItems: NavItem[] = [
    {
        title: 'Bienvenida',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Alumnos',
        href: '/alumnos',
        icon: GraduationCap,
    },
    {
        title: 'Especialidades',
        href: '/especialidades',
        icon: GraduationCap,
    },
    {
        title: 'Profesores',
        href: '/profesores',
        icon: GraduationCap,
    },
    {
        title: 'Materias',
        href: '/materias',
        icon: BookOpen,
    },
    {
        title: 'Edificios',
        href: '/edificios',
        icon: Building2,
    },
    {
        title: 'Horarios',
        href: '/horarios',
        icon: Clock,
    },
    {
        title: 'Matrículas',
        href: '/matriculas',
        icon: ClipboardList,
    },
];

const footerNavItems: NavItem[] = [

];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>

    <slot />
</template>
