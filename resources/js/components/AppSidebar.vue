<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { LayoutGrid, Users, Moon, Sun, Settings } from 'lucide-vue-next';
import AppLogo from '@/components/AppLogo.vue';
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
import { useAppearance } from '@/composables/useAppearance';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

const { appearance, updateAppearance } = useAppearance();

const toggleTheme = () => {
    updateAppearance(appearance.value === 'dark' ? 'light' : 'dark');
};

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Entities',
        href: '/entities',
        icon: Users,
        items: [
            {
                title: 'Customers',
                href: '/entities?type=customer',
            },
            {
                title: 'Suppliers',
                href: '/entities?type=supplier',
            },
        ],
    },
    {
        title: 'Settings',
        href: '#',
        icon: Settings,
        items: [
            {
                title: 'Articles',
                href: '/articles',
            },
        ],
    },
];

const footerNavItems: NavItem[] = [];
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
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton
                        @click="toggleTheme"
                        tooltip="Toggle Theme"
                    >
                        <Moon v-if="appearance === 'dark'" class="size-4" />
                        <Sun v-else class="size-4" />

                        <span>{{
                            appearance === 'dark' ? 'Light Mode' : 'Dark Mode'
                        }}</span>

                        <div
                            class="ml-auto flex h-5 w-9 shrink-0 items-center rounded-full p-0.5 transition-colors duration-200 ease-in-out group-data-[collapsible=icon]:hidden"
                            :class="
                                appearance === 'dark'
                                    ? 'bg-indigo-600 dark:bg-indigo-500'
                                    : 'bg-gray-300 dark:bg-gray-700'
                            "
                        >
                            <div
                                class="h-4 w-4 transform rounded-full bg-white shadow transition duration-200 ease-in-out"
                                :class="
                                    appearance === 'dark'
                                        ? 'translate-x-4'
                                        : 'translate-x-0'
                                "
                            ></div>
                        </div>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>

            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
