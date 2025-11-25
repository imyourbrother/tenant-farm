import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import { Building2, ChevronRight } from 'lucide-react';

export default function FarmSelection({ auth, farms }) {
    const { post, processing } = useForm();

    const selectFarm = (farmId) => {
        post(route('farms.select', farmId));
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Select Farm
                </h2>
            }
        >
            <Head title="Select Farm" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="mb-6">
                        <h3 className="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            Choose a Farm to Manage
                        </h3>
                        <p className="mt-2 text-gray-600 dark:text-gray-400">
                            Select one of your assigned farms to view its dashboard and manage operations.
                        </p>
                    </div>

                    {farms && farms.length > 0 ? (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {farms.map((farm) => (
                                <button
                                    key={farm.id}
                                    onClick={() => selectFarm(farm.id)}
                                    disabled={processing}
                                    className="group relative bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed text-left"
                                >
                                    <div className="p-6">
                                        <div className="flex items-start justify-between">
                                            <div className="flex items-center space-x-3">
                                                <div className="bg-green-100 dark:bg-green-900 p-3 rounded-lg">
                                                    <Building2 className="w-6 h-6 text-green-600 dark:text-green-400" />
                                                </div>
                                                <div>
                                                    <h3 className="text-lg font-bold text-gray-900 dark:text-gray-100 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">
                                                        {farm.name}
                                                    </h3>
                                                    <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                        {farm.slug}
                                                    </p>
                                                </div>
                                            </div>
                                            <ChevronRight className="w-5 h-5 text-gray-400 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors" />
                                        </div>

                                        {farm.pivot && farm.pivot.role_id && (
                                            <div className="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    Role ID: {farm.pivot.role_id}
                                                </span>
                                            </div>
                                        )}
                                    </div>

                                    <div className="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-green-400 to-blue-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-200"></div>
                                </button>
                            ))}
                        </div>
                    ) : (
                        <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-12 text-center">
                                <Building2 className="w-16 h-16 text-gray-400 mx-auto mb-4" />
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                    No Farms Assigned
                                </h3>
                                <p className="text-gray-600 dark:text-gray-400">
                                    You don't have access to any farms yet. Please contact your administrator.
                                </p>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
