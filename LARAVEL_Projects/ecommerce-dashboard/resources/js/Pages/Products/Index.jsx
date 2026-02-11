import React, { useState } from 'react';
import AppLayout from '@/Layouts/AppLayout';
import { Link, router, useForm } from '@inertiajs/react';

export default function ProductsIndex({ products, categories, filters }) {
    const [filterOpen, setFilterOpen] = useState(false);
    const { delete: destroy } = useForm();

    const formatCurrency = (amount) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount).replace('IDR', 'Rp');
    };

    const handleDelete = (id) => {
        if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
            destroy(route('products.destroy', id));
        }
    };

    const handleSearch = (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const params = Object.fromEntries(formData);
        router.get(route('products.index'), params, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    return (
        <AppLayout title="Produk Saya">
            <div className="p-6">
                <div className="mb-6">
                    <h2 className="text-2xl font-bold text-gray-800">Daftar Produk</h2>
                    <p className="text-gray-600 mt-1">Kelola produk yang dijual di toko Anda</p>
                </div>

                <div className="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div className="p-6">
                        <div className="flex justify-between items-center mb-6">
                            <h3 className="text-lg font-semibold text-gray-800">Semua Produk</h3>
                            <Link 
                                href={route('products.create')}
                                className="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-md text-sm flex items-center gap-2">
                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Produk
                            </Link>
                        </div>

                        <div className="mb-6">,
                                    <div className="flex flex-col md:flex-row gap-4 items-start md:items-center">
                                        {/* Search Input */}
                                        <div className="flex-1 max-w-md">
                                            <form onSubmit={handleSearch} className="relative">
                                                <div className="relative">
                                                    <div className="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                        <svg className="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                        </svg>
                                                    </div>
                                                    <input 
                                                        type="text" 
                                                        name="search" 
                                                        defaultValue={filters.search}
                                                        placeholder="Cari nama produk..."
                                                        className="w-full pl-10 pr-4 py-2 border-gray-300 rounded-lg text-sm focus:ring focus:ring-blue-200"
                                                    />
                                                </div>
                                                <input type="hidden" name="filter_price" value={filters.filter_price || ''} />
                                                <input type="hidden" name="filter_category" value={filters.filter_category || ''} />
                                                <input type="hidden" name="filter_stock" value={filters.filter_stock || ''} />
                                            </form>
                                        </div>

                                        {/* Filter Toggle Button */}
                                        <button 
                                            type="button" 
                                            onClick={() => setFilterOpen(!filterOpen)}
                                            className="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                                </path>
                                            </svg>
                                            Filter
                                            <svg className={`w-4 h-4 transition-transform ${filterOpen ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    {/* Filter Section */}
                                    {filterOpen && (
                                        <div className="mt-4 pt-4 border-t border-gray-200">
                                            <form onSubmit={handleSearch}>
                                                <input type="hidden" name="search" value={filters.search || ''} />

                                                <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                                                    <div>
                                                        <label className="block text-sm font-bold text-gray-700 mb-1">Maksimal Harga</label>
                                                        <select 
                                                            name="filter_price"
                                                            defaultValue={filters.filter_price || ''}
                                                            className="w-full border-gray-300 rounded text-sm focus:ring focus:ring-blue-200">
                                                            <option value="">Semua Harga</option>
                                                            <option value="50000">Rp 50.000 ke bawah</option>
                                                            <option value="100000">Rp 100.000 ke bawah</option>
                                                            <option value="500000">Rp 500.000 ke bawah</option>
                                                            <option value="1000000">Rp 1.000.000 ke bawah</option>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label className="block text-sm font-bold text-gray-700 mb-1">Kategori</label>
                                                        <select 
                                                            name="filter_category"
                                                            defaultValue={filters.filter_category || ''}
                                                            className="w-full border-gray-300 rounded text-sm focus:ring focus:ring-blue-200">
                                                            <option value="">Semua Kategori</option>
                                                            {categories.map(category => (
                                                                <option key={category.id} value={category.id}>
                                                                    {category.name}
                                                                </option>
                                                            ))}
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label className="block text-sm font-bold text-gray-700 mb-1">Stok Menipis</label>
                                                        <select 
                                                            name="filter_stock"
                                                            defaultValue={filters.filter_stock || ''}
                                                            className="w-full border-gray-300 rounded text-sm focus:ring focus:ring-blue-200">
                                                            <option value="">Semua Stok</option>
                                                            <option value="20">Kurang dari 20</option>
                                                            <option value="50">Kurang dari 50</option>
                                                            <option value="100">Kurang dari 100</option>
                                                        </select>
                                                    </div>

                                                    <div className="flex items-end gap-2">
                                                        <button 
                                                            type="submit"
                                                            className="bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-indigo-700 font-medium transition w-full md:w-auto">
                                                            Terapkan Filter
                                                        </button>
                                                        <Link
                                                            href={route('products.index')}
                                                            className="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-300 font-medium transition">
                                                            Reset
                                                        </Link>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    )}
                                </div>

                                {/* Products Table */}
                                <div className="overflow-x-auto border border-gray-200 rounded-lg">
                                    <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {products.data.map(product => (
                                            <tr key={product.id}>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="flex items-center">
                                                        {product.image && (
                                                            <img 
                                                                src={`/storage/${product.image}`} 
                                                                alt={product.name}
                                                                className="h-10 w-10 rounded object-cover mr-3"
                                                            />
                                                        )}
                                                        <div>
                                                            <div className="text-sm font-medium text-gray-900">{product.name}</div>
                                                            <div className="text-sm text-gray-500">{product.slug}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {product.category?.name || '-'}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                                    {formatCurrency(product.price)}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                                        product.stock < 10 ? 'bg-red-100 text-red-800' : 
                                                        product.stock < 50 ? 'bg-yellow-100 text-yellow-800' : 
                                                        'bg-green-100 text-green-800'
                                                    }`}>
                                                        {product.stock}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <Link 
                                                        href={route('products.edit', product.id)}
                                                        className="text-indigo-600 hover:text-indigo-900 mr-3">
                                                        Edit
                                                    </Link>
                                                    <button 
                                                        onClick={() => handleDelete(product.id)}
                                                        className="text-red-600 hover:text-red-900">
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>

                                {/* Pagination */}
                                {products.links.length > 3 && (
                                    <div className="px-6 py-4 flex items-center justify-between border-t border-gray-200">
                                        <div className="flex-1 flex justify-between sm:hidden">
                                            {products.prev_page_url && (
                                                <Link href={products.prev_page_url} className="btn-pagination">Previous</Link>
                                            )}
                                            {products.next_page_url && (
                                                <Link href={products.next_page_url} className="btn-pagination">Next</Link>
                                            )}
                                        </div>
                                        <div className="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                            <div>
                                                <p className="text-sm text-gray-700">
                                                    Showing <span className="font-medium">{products.from}</span> to{' '}
                                                    <span className="font-medium">{products.to}</span> of{' '}
                                                    <span className="font-medium">{products.total}</span> results
                                                </p>
                                            </div>
                                            <div className="flex gap-1">
                                                {products.links.map((link, index) => (
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
