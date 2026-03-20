<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    LayoutDashboard,
    Users,
    FileText,
    ShoppingCart,
    ArrowRight,
} from 'lucide-vue-next';
import { dashboard, login, register } from '@/routes';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="Welcome to ManageCorp" />

    <div
        class="flex min-h-screen flex-col bg-gray-50 text-gray-900 selection:bg-indigo-500 selection:text-white dark:bg-gray-950 dark:text-gray-100"
    >
        <div
            class="absolute inset-0 -z-10 h-full w-full bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] mask-[radial-gradient(ellipse_50%_50%_at_50%_50%,#000_70%,transparent_100%)] bg-size-[16px_16px] dark:bg-[radial-gradient(#1f2937_1px,transparent_1px)]"
        ></div>
        <div
            class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80"
            aria-hidden="true"
        >
            <div
                class="relative left-[calc(50%-11rem)] aspect-1155/678 w-144.5 -translate-x-1/2 rotate-30 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%-30rem)] sm:w-288.75 dark:opacity-20"
                style="
                    clip-path: polygon(
                        74.1% 44.1%,
                        100% 61.6%,
                        97.5% 26.9%,
                        85.5% 0.1%,
                        80.7% 2%,
                        72.5% 32.5%,
                        60.2% 62.4%,
                        52.4% 68.1%,
                        47.5% 58.3%,
                        45.2% 34.5%,
                        27.5% 76.7%,
                        0.1% 64.9%,
                        17.9% 100%,
                        27.6% 76.8%,
                        76.1% 97.7%,
                        74.1% 44.1%
                    );
                "
            ></div>
        </div>

        <header class="absolute inset-x-0 top-0 z-50">
            <nav
                class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8"
                aria-label="Global"
            >
                <div class="flex lg:flex-1">
                    <a href="#" class="-m-1.5 flex items-center gap-2 p-1.5">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600"
                        >
                            <LayoutDashboard class="h-5 w-5 text-white" />
                        </div>
                        <span
                            class="text-xl font-bold tracking-tight text-gray-900 dark:text-white"
                            >ManageCorp</span
                        >
                    </a>
                </div>
                <div class="flex flex-1 items-center justify-end gap-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="text-sm leading-6 font-semibold text-gray-900 hover:text-indigo-600 dark:text-gray-100 dark:hover:text-indigo-400"
                    >
                        Go to Dashboard <span aria-hidden="true">&rarr;</span>
                    </Link>
                    <template v-else>
                        <Link
                            :href="login()"
                            class="text-sm leading-6 font-semibold text-gray-900 hover:text-indigo-600 dark:text-gray-100 dark:hover:text-indigo-400"
                        >
                            Log in
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="register()"
                            class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                        >
                            Get Started
                        </Link>
                    </template>
                </div>
            </nav>
        </header>

        <main class="flex-1">
            <div class="relative isolate w-full overflow-hidden pt-24 lg:pt-2">
                <div
                    class="mx-auto max-w-7xl px-6 pt-10 pb-24 sm:pb-32 lg:flex lg:px-8 lg:py-40"
                >
                    <div
                        class="mx-auto max-w-2xl text-center lg:mx-0 lg:max-w-xl lg:shrink-0 lg:pt-8 lg:text-left"
                    >
                        <div class="mt-8 sm:mt-12 lg:mt-0">
                            <span
                                class="inline-flex rounded-full bg-indigo-600/10 px-3 py-1 text-sm leading-6 font-semibold text-indigo-600 ring-1 ring-indigo-600/20 ring-inset dark:bg-indigo-500/10 dark:text-indigo-400 dark:ring-indigo-500/20"
                            >
                                v1.0 Release
                            </span>
                        </div>
                        <h1
                            class="mt-10 text-4xl font-extrabold tracking-tight text-gray-900 sm:text-6xl dark:text-white"
                        >
                            The Operating System for Your Business
                        </h1>
                        <p
                            class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-400"
                        >
                            ManageCorp streamlines your workflow. Track customer
                            orders, automate supplier invoices, and get
                            real-time financial insights in one beautiful,
                            integrated platform.
                        </p>
                        <div
                            class="mt-10 flex items-center justify-center gap-x-6 lg:justify-start"
                        >
                            <Link
                                v-if="$page.props.auth.user"
                                :href="dashboard()"
                                class="flex items-center gap-2 rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                            >
                                Open App <ArrowRight class="h-4 w-4" />
                            </Link>
                            <template v-else>
                                <Link
                                    v-if="canRegister"
                                    :href="register()"
                                    class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                                >
                                    Start for free
                                </Link>
                                <Link
                                    :href="login()"
                                    class="flex items-center gap-1 text-sm leading-6 font-semibold text-gray-900 hover:text-indigo-600 dark:text-gray-100 dark:hover:text-indigo-400"
                                >
                                    Sign in <ArrowRight class="h-4 w-4" />
                                </Link>
                            </template>
                        </div>
                    </div>

                    <div
                        class="mx-auto mt-16 flex max-w-2xl sm:mt-24 lg:mt-0 lg:mr-0 lg:ml-10 lg:max-w-none lg:flex-none xl:ml-32"
                    >
                        <div
                            class="max-w-3xl flex-none sm:max-w-5xl lg:max-w-none"
                        >
                            <div
                                class="rounded-xl bg-gray-900/5 p-2 shadow-2xl ring-1 ring-gray-900/10 backdrop-blur-3xl ring-inset lg:-m-4 lg:rounded-2xl lg:p-4 dark:bg-white/5 dark:ring-white/10"
                            >
                                <div
                                    class="flex h-75 w-[320px] flex-col overflow-hidden rounded-md bg-white shadow-2xl ring-1 ring-gray-900/10 sm:h-100 sm:w-125 lg:w-162.5 dark:bg-gray-950 dark:ring-white/10"
                                >
                                    <div
                                        class="flex items-center gap-2 border-b border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-900"
                                    >
                                        <div
                                            class="h-3 w-3 rounded-full bg-red-400"
                                        ></div>
                                        <div
                                            class="h-3 w-3 rounded-full bg-amber-400"
                                        ></div>
                                        <div
                                            class="h-3 w-3 rounded-full bg-green-400"
                                        ></div>
                                    </div>

                                    <div
                                        class="flex flex-1 flex-col gap-4 bg-gray-50/50 p-4 sm:p-6 dark:bg-gray-950/50"
                                    >
                                        <div
                                            class="h-6 w-32 rounded-md bg-gray-200 dark:bg-gray-800"
                                        ></div>

                                        <div
                                            class="grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4"
                                        >
                                            <div
                                                class="flex h-20 flex-col justify-between rounded-lg border border-gray-100 bg-white p-3 shadow-sm sm:h-24 sm:p-4 dark:border-gray-800 dark:bg-gray-900"
                                            >
                                                <div
                                                    class="h-3 w-12 rounded bg-gray-100 sm:h-4 sm:w-16 dark:bg-gray-800"
                                                ></div>
                                                <div
                                                    class="h-5 w-20 rounded bg-gray-200 sm:h-6 sm:w-24 dark:bg-gray-700"
                                                ></div>
                                            </div>
                                            <div
                                                class="flex h-20 flex-col justify-between rounded-lg border border-gray-100 bg-white p-3 shadow-sm sm:h-24 sm:p-4 dark:border-gray-800 dark:bg-gray-900"
                                            >
                                                <div
                                                    class="h-3 w-12 rounded bg-gray-100 sm:h-4 sm:w-16 dark:bg-gray-800"
                                                ></div>
                                                <div
                                                    class="h-5 w-20 rounded bg-gray-200 sm:h-6 sm:w-24 dark:bg-gray-700"
                                                ></div>
                                            </div>
                                            <div
                                                class="hidden h-24 flex-col justify-between rounded-lg border border-gray-100 bg-white p-4 shadow-sm sm:flex dark:border-gray-800 dark:bg-gray-900"
                                            >
                                                <div
                                                    class="h-4 w-16 rounded bg-gray-100 dark:bg-gray-800"
                                                ></div>
                                                <div
                                                    class="h-6 w-24 rounded bg-gray-200 dark:bg-gray-700"
                                                ></div>
                                            </div>
                                        </div>

                                        <div
                                            class="mt-2 flex-1 rounded-lg border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900"
                                        ></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mx-auto mt-8 max-w-7xl px-6 pb-24 sm:mt-16 lg:px-8">
                <div class="mx-auto max-w-2xl lg:text-center">
                    <h2
                        class="text-base leading-7 font-semibold text-indigo-600 dark:text-indigo-400"
                    >
                        Everything you need
                    </h2>
                    <p
                        class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl dark:text-white"
                    >
                        No more spreadsheets. Just results.
                    </p>
                    <p
                        class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-400"
                    >
                        ManageCorp provides a complete suite of tools to manage
                        your commercial relationships, track your cash flow, and
                        keep your business organized.
                    </p>
                </div>

                <div
                    class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none"
                >
                    <dl
                        class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3"
                    >
                        <div class="flex flex-col">
                            <dt
                                class="flex items-center gap-x-3 text-base leading-7 font-semibold text-gray-900 dark:text-white"
                            >
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600"
                                >
                                    <Users
                                        class="h-6 w-6 text-white"
                                        aria-hidden="true"
                                    />
                                </div>
                                Unified Entities
                            </dt>
                            <dd
                                class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600 dark:text-gray-400"
                            >
                                <p class="flex-auto">
                                    Manage all your customers and suppliers from
                                    a single, organized database. Track contact
                                    details, VAT numbers, and full commercial
                                    history.
                                </p>
                            </dd>
                        </div>

                        <div class="flex flex-col">
                            <dt
                                class="flex items-center gap-x-3 text-base leading-7 font-semibold text-gray-900 dark:text-white"
                            >
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600"
                                >
                                    <ShoppingCart
                                        class="h-6 w-6 text-white"
                                        aria-hidden="true"
                                    />
                                </div>
                                Order Processing
                            </dt>
                            <dd
                                class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600 dark:text-gray-400"
                            >
                                <p class="flex-auto">
                                    Create professional quotes, convert them to
                                    customer orders, and generate purchase
                                    orders for your suppliers with just a few
                                    clicks.
                                </p>
                            </dd>
                        </div>

                        <div class="flex flex-col">
                            <dt
                                class="flex items-center gap-x-3 text-base leading-7 font-semibold text-gray-900 dark:text-white"
                            >
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600"
                                >
                                    <FileText
                                        class="h-6 w-6 text-white"
                                        aria-hidden="true"
                                    />
                                </div>
                                Invoice & Attachments
                            </dt>
                            <dd
                                class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600 dark:text-gray-400"
                            >
                                <p class="flex-auto">
                                    Keep track of your pending supplier
                                    invoices. Upload original PDF documents,
                                    attach them to records, and notify suppliers
                                    directly via email.
                                </p>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </main>

        <footer
            class="border-t border-gray-200 bg-gray-50 py-8 text-center text-sm text-gray-500 dark:border-gray-800 dark:bg-gray-950 dark:text-gray-400"
        >
            <p>
                &copy; {{ new Date().getFullYear() }} ManageCorp Inc. All rights
                reserved.
            </p>
        </footer>
    </div>
</template>
