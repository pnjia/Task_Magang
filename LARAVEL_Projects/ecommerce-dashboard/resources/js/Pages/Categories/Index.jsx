// Categories Index Page
import AppLayout from '@/Layouts/AppLayout';
import { Link, useForm } from '@inertiajs/react';

export default function CategoriesIndex({ categories }) {
    const { delete: destroy } = useForm();

    const handleDelete = (id) => {
        if (confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
            destroy(route('categories.destroy', id));
        }
    };

    return (
        <AppLayout title="Kategori Produk">
            <div className="p-6">
                <div className="mb-6">
                    <h2 className="text-2xl font-bold text-gray-800">Kategori Produk</h2>
                    <p className="text-gray-600 mt-1">Kelola kategori untuk mengorganisir produk</p>
                </div>

                <div className="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div className="p-6">
                        <div className="flex justify-between items-center mb-6">
                            <h3 className="text-lg font-semibold text-gray-800">Semua Kategori</h3>
                            <Link 
                                href={route('categories.create')}
                                className="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-md text-sm flex items-center gap-2">
                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Kategori
                            </Link>
                        </div>

                        <div className="overflow-x-auto border border-gray-200 rounded-lg">
                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nama Kategori
                                            </th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Slug
                                            </th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {categories.data.map(category => (
                                            <tr key={category.id}>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="text-sm font-medium text-gray-900">{category.name}</div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {category.slug}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <Link 
                                                        href={route('categories.edit', category.id)}
                                                        className="text-indigo-600 hover:text-indigo-900 mr-3">
                                                        Edit
                                                    </Link>
                                                    <button 
                                                        onClick={() => handleDelete(category.id)}
                                                        className="text-red-600 hover:text-red-900">
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>

                                {/* Pagination */}
                                {categories.links.length > 3 && (
                                    <div className="px-6 py-4 flex items-center justify-between border-t border-gray-200">
                                        <div className="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                            <div>
                                                <p className="text-sm text-gray-700">
                                                    Showing <span className="font-medium">{categories.from}</span> to{' '}
                                                    <span className="font-medium">{categories.to}</span> of{' '}
                                                    <span className="font-medium">{categories.total}</span> results
                                                </p>
                                            </div>
                                            <div className="flex gap-1">
                                                {categories.links.map((link, index) => (
                                                    link.url ? (
                                                        <Link
                                                            key={index}
                                                            href={link.url}
                                                            className={`px-3 py-1 text-sm rounded ${
                                                                link.active 
                                                                    ? 'bg-indigo-600 text-white' 
                                                                    : 'bg-white text-gray-700 hover:bg-gray-50 border'
                                                            }`}
                                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                                        />
                                                    ) : (
                                                        <span
                                                            key={index}
                                                            className="px-3 py-1 text-sm text-gray-400"
                                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                                        />
                                                    )
                                                ))}
                                            </div>
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
        </AppLayout>
    );
}
