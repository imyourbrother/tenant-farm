import { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import {
    LayoutDashboard,
    Building2,
    Users,
    Wheat,
    Settings,
    Menu,
    X,
    LogOut,
    ChevronDown
} from 'lucide-react';

export default function AdminLayout({ user, children }) {
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const { url } = usePage();

    const navigation = [
        { name: 'Dashboard', href: '/admin/dashboard', icon: LayoutDashboard },
        { name: 'Farms', href: '/admin/farms', icon: Building2 },
        { name: 'Users', href: '/admin/users', icon: Users },
        { name: 'Harvests', href: '/admin/harvests', icon: Wheat },
        { name: 'Settings', href: '/admin/settings', icon: Settings },
    ];

    const isActive = (href) => url.startsWith(href);

    return (
        <div className="min-h-screen bg-gray-50 dark:bg-gray-900">
            {/* Sidebar for desktop */}
            <div className="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col">
                <div className="flex flex-col flex-grow bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 pt-5 pb-4 overflow-y-auto">
                    {/* Logo */}
                    <div className="flex items-center flex-shrink-0 px-6">
                        <Building2 className="h-8 w-8 text-green-600" />
                        <span className="ml-2 text-xl font-bold text-gray-900 dark:text-white">
                            Farm MS
                        </span>
                    </div>

                    {/* Navigation */}
                    <nav className="mt-8 flex-1 px-3 space-y-1">
                        {navigation.map((item) => {
                            const Icon = item.icon;
                            const active = isActive(item.href);
                            return (
                                <Link
                                    key={item.name}
                                    href={item.href}
                                    className={`
                                        group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                                        ${active
                                            ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400'
                                            : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'
                                        }
                                    `}
                                >
                                    <Icon className={`mr-3 h-5 w-5 flex-shrink-0 ${active ? 'text-green-600 dark:text-green-400' : 'text-gray-400'}`} />
                                    {item.name}
                                </Link>
                            );
                        })}
                    </nav>

                    {/* User section */}
                    <div className="flex-shrink-0 flex border-t border-gray-200 dark:border-gray-700 p-4">
                        <div className="flex items-center w-full">
                            <div className="flex-shrink-0">
                                <div className="h-10 w-10 rounded-full bg-green-600 flex items-center justify-center text-white font-semibold">
                                    {user.name.charAt(0).toUpperCase()}
                                </div>
                            </div>
                            <div className="ml-3 flex-1">
                                <p className="text-sm font-medium text-gray-900 dark:text-white">
                                    {user.name}
                                </p>
                                <p className="text-xs text-gray-500 dark:text-gray-400">
                                    {user.email}
                                </p>
                            </div>
                            <Link
                                href="/logout"
                                method="post"
                                as="button"
                                className="ml-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                            >
                                <LogOut className="h-5 w-5" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            {/* Mobile sidebar */}
            <div className={`lg:hidden ${sidebarOpen ? 'block' : 'hidden'}`}>
                <div className="fixed inset-0 flex z-40">
                    <div className="fixed inset-0 bg-gray-600 bg-opacity-75" onClick={() => setSidebarOpen(false)}></div>
                    <div className="relative flex-1 flex flex-col max-w-xs w-full bg-white dark:bg-gray-800">
                        <div className="absolute top-0 right-0 -mr-12 pt-2">
                            <button
                                onClick={() => setSidebarOpen(false)}
                                className="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                            >
                                <X className="h-6 w-6 text-white" />
                            </button>
                        </div>
                        <div className="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                            <div className="flex-shrink-0 flex items-center px-4">
                                <Building2 className="h-8 w-8 text-green-600" />
                                <span className="ml-2 text-xl font-bold text-gray-900 dark:text-white">
                                    Farm MS
                                </span>
                            </div>
                            <nav className="mt-5 px-2 space-y-1">
                                {navigation.map((item) => {
                                    const Icon = item.icon;
                                    const active = isActive(item.href);
                                    return (
                                        <Link
                                            key={item.name}
                                            href={item.href}
                                            className={`
                                                group flex items-center px-2 py-2 text-base font-medium rounded-md
                                                ${active
                                                    ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400'
                                                    : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'
                                                }
                                            `}
                                        >
                                            <Icon className={`mr-4 h-6 w-6 ${active ? 'text-green-600 dark:text-green-400' : 'text-gray-400'}`} />
                                            {item.name}
                                        </Link>
                                    );
                                })}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            {/* Main content */}
            <div className="lg:pl-64 flex flex-col flex-1">
                {/* Top bar */}
                <div className="sticky top-0 z-10 flex-shrink-0 flex h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <button
                        onClick={() => setSidebarOpen(true)}
                        className="px-4 border-r border-gray-200 dark:border-gray-700 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500 lg:hidden"
                    >
                        <Menu className="h-6 w-6" />
                    </button>
                    <div className="flex-1 px-4 flex justify-between items-center">
                        <div className="flex-1"></div>
                        <div className="ml-4 flex items-center md:ml-6">
                            <span className="text-sm text-gray-700 dark:text-gray-300">
                                {new Date().toLocaleDateString('en-US', {
                                    weekday: 'long',
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                })}
                            </span>
                        </div>
                    </div>
                </div>

                {/* Page content */}
                <main className="flex-1">
                    {children}
                </main>
            </div>
        </div>
    );
}
