<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { BookOpen, FileUpIcon, FolderGit2, LayoutGrid, MapIcon, MapPin, Settings } from '@lucide/vue';
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
import { dashboard, fileUpload } from '@/routes';
import type { NavItem } from '@/types';
import metadataConfiguration from '@/routes/metadata-configuration';
import studyConfiguration from '@/routes/study-configuration';
import countryConfiguration from '@/routes/country-configuration';
import siteConfiguration from '@/routes/site-configuration';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'File Upload',
        href: fileUpload(),
        icon: FileUpIcon,
    }     
];

const configNavItems: NavItem[] = [
    {
        title: 'Study Configuration',
        href: studyConfiguration.index(),
        icon: Settings,
    },
    {
        title: 'Metadata Configuration',
        href: metadataConfiguration.index(),
        icon: Settings,
    },
    {
        title: 'Country Configuration',
        href: countryConfiguration.index(),
        icon: MapIcon,
    },    
    {
        title: 'Site Configuration',
        href: siteConfiguration.index(),
        icon: MapPin,
    },        
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
            <NavMain :items="mainNavItems" :config-items="configNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="[]" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
