import AdminLayout from '@/Layouts/AdminLayout';
import { Head } from '@inertiajs/react';
import { BarChart3, TrendingUp, Users, Wheat } from 'lucide-react';

export default function Dashboard({ auth, isSuperadmin, currentFarm, stats, recentHarvests, farms, harvestsByCrop }) {
    return (
        <AdminLayout user={auth.user}>
            <Head title="Dashboard" />

            <div className="py-6 px-4 sm:px-6 lg:px-8">
                <div className="mb-6">
                    <h1 className="text-2xl font-bold text-gray-900 dark:text-white">
                        {isSuperadmin ? 'Superadmin Dashboard' : `Dashboard - ${currentFarm?.name}`}
                    </h1>
                    <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {isSuperadmin ? 'Overview of all farms and system statistics' : 'Overview of your farm operations'}
                    </p>
                </div>
                {/* Stats Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    {isSuperadmin ? (
                        <>
                            <StatCard
                                title="Total Farms"
                                value={stats.total_farms}
                                icon={<BarChart3 className="w-8 h-8" />}
                                color="blue"
                            />
                            <StatCard
                                title="Total Users"
                                value={stats.total_users}
                                icon={<Users className="w-8 h-8" />}
                                color="green"
                            />
                            <StatCard
                                title="Total Harvests"
                                value={stats.total_harvests}
                                icon={<Wheat className="w-8 h-8" />}
                                color="yellow"
                            />
                            <StatCard
                                title="Total Quantity"
                                value={stats.total_quantity?.toLocaleString()}
                                icon={<TrendingUp className="w-8 h-8" />}
                                color="purple"
                            />
                        </>
                    ) : (
                        <>
                            <StatCard
                                title="Total Harvests"
                                value={stats.total_harvests}
                                icon={<Wheat className="w-8 h-8" />}
                                color="blue"
                            />
                            <StatCard
                                title="Total Quantity"
                                value={stats.total_quantity?.toLocaleString()}
                                icon={<TrendingUp className="w-8 h-8" />}
                                color="green"
                            />
                            <StatCard
                                title="Recent (7 days)"
                                value={stats.recent_harvests_count}
                                icon={<BarChart3 className="w-8 h-8" />}
                                color="yellow"
                            />
                        </>
                    )}
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {/* Recent Harvests */}
                    <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            <h3 className="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                Recent Harvests
                            </h3>
                            <div className="space-y-3">
                                {recentHarvests && recentHarvests.length > 0 ? (
                                    recentHarvests.map((harvest) => (
                                        <div
                                            key={harvest.id}
                                            className="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg"
                                        >
                                            <div>
                                                <p className="font-medium text-gray-900 dark:text-gray-100">
                                                    {harvest.crop_name}
                                                </p>
                                                <p className="text-sm text-gray-500 dark:text-gray-400">
                                                    {harvest.harvested_at}
                                                    {isSuperadmin && harvest.farm_name && (
                                                        <span className="ml-2 text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded">
                                                            {harvest.farm_name}
                                                        </span>
                                                    )}
                                                </p>
                                            </div>
                                            <div className="text-right">
                                                <p className="font-semibold text-gray-900 dark:text-gray-100">
                                                    {harvest.quantity.toLocaleString()}
                                                </p>
                                                <p className="text-xs text-gray-500 dark:text-gray-400">units</p>
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <p className="text-gray-500 dark:text-gray-400 text-center py-4">
                                        No harvests recorded yet
                                    </p>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Farms List (Superadmin) or Harvests by Crop (Manager) */}
                    <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            <h3 className="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                {isSuperadmin ? 'Farms Overview' : 'Harvests by Crop'}
                            </h3>
                            <div className="space-y-3">
                                {isSuperadmin ? (
                                    farms && farms.length > 0 ? (
                                        farms.map((farm) => (
                                            <div
                                                key={farm.id}
                                                className="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg"
                                            >
                                                <div>
                                                    <p className="font-medium text-gray-900 dark:text-gray-100">
                                                        {farm.name}
                                                    </p>
                                                    <p className="text-sm text-gray-500 dark:text-gray-400">
                                                        {farm.slug}
                                                    </p>
                                                </div>
                                                <div className="text-right">
                                                    <p className="font-semibold text-gray-900 dark:text-gray-100">
                                                        {farm.harvests_count}
                                                    </p>
                                                    <p className="text-xs text-gray-500 dark:text-gray-400">
                                                        harvests
                                                    </p>
                                                </div>
                                            </div>
                                        ))
                                    ) : (
                                        <p className="text-gray-500 dark:text-gray-400 text-center py-4">
                                            No farms available
                                        </p>
                                    )
                                ) : (
                                    harvestsByCrop && harvestsByCrop.length > 0 ? (
                                        harvestsByCrop.map((item, index) => (
                                            <div
                                                key={index}
                                                className="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg"
                                            >
                                                <div>
                                                    <p className="font-medium text-gray-900 dark:text-gray-100">
                                                        {item.crop_name}
                                                    </p>
                                                    <p className="text-sm text-gray-500 dark:text-gray-400">
                                                        {item.count} harvest{item.count !== 1 ? 's' : ''}
                                                    </p>
                                                </div>
                                                <div className="text-right">
                                                    <p className="font-semibold text-gray-900 dark:text-gray-100">
                                                        {item.total_quantity.toLocaleString()}
                                                    </p>
                                                    <p className="text-xs text-gray-500 dark:text-gray-400">
                                                        total units
                                                    </p>
                                                </div>
                                            </div>
                                        ))
                                    ) : (
                                        <p className="text-gray-500 dark:text-gray-400 text-center py-4">
                                            No harvest data available
                                        </p>
                                    )
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}

function StatCard({ title, value, icon, color }) {
    const colorClasses = {
        blue: 'bg-blue-500',
        green: 'bg-green-500',
        yellow: 'bg-yellow-500',
        purple: 'bg-purple-500',
    };

    return (
        <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div className="p-6">
                <div className="flex items-center justify-between">
                    <div>
                        <p className="text-sm font-medium text-gray-500 dark:text-gray-400">{title}</p>
                        <p className="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                            {value || 0}
                        </p>
                    </div>
                    <div className={`${colorClasses[color]} p-3 rounded-full text-white`}>
                        {icon}
                    </div>
                </div>
            </div>
        </div>
    );
}
