import React, { useState } from 'react';
import AppLayout from '@/Layouts/AppLayout';
import { Link, useForm } from '@inertiajs/react';

export default function ProductsCreate({ categories }) {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        slug: '',
        category_id: '',
        price: '',
        stock: 0,
        description: '',
        image: null,
    });

    const [preview, setPreview] = useState(null);

    const generateSlug = (name) => {
        return name
            .toLowerCase()
            .replace(/ /g, '-')
            .replace(/[^\w-]+/g, '');
    };

    const handleNameChange = (e) => {
        const name = e.target.value;
        setData({
            ...data,
            name: name,
            slug: generateSlug(name),
        });
    };

    const handleImageChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            setData('image', file);
            setPreview(URL.createObjectURL(file));
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('products.store'));
    };

    return (
        <AppLayout title="Tambah Produk Baru">
            <div className="p-6">
                <div className="mb-6 flex items-center gap-2 text-sm text-gray-600">
                    <Link href={route('products.index')} className="hover:text-gray-900">
                        Produk
                    </Link>
                    <span>/</span>
                    <span className="text-gray-900">Tambah Baru</span>
                </div>

                <div className="mb-6">
                    <h2 className="text-2xl font-bold text-gray-800">Tambah Produk Baru</h2>
                    <p className="text-gray-600 mt-1">Lengkapi form untuk menambahkan produk</p>
                </div>

                <div className="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div className="p-6">

                            <form onSubmit={handleSubmit} enctype="multipart/form-data">
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {/* Left Column */}
                                    <div className="space-y-3">
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700">Nama Produk</label>
                                            <input 
                                                type="text" 
                                                value={data.name}
                                                onChange={handleNameChange}
                                                className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm py-2"
                                                required
                                            />
                                            {errors.name && <div className="text-red-600 text-sm mt-1">{errors.name}</div>}
                                        </div>

                                        <div>
                                            <label className="block text-sm font-medium text-gray-700">Slug (URL)</label>
                                            <input 
                                                type="text" 
                                                value={data.slug}
                                                onChange={e => setData('slug', e.target.value)}
                                                className="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm text-sm py-2"
                                                required
                                            />
                                            {errors.slug && <div className="text-red-600 text-sm mt-1">{errors.slug}</div>}
                                        </div>

                                        <div>
                                            <label className="block text-sm font-medium text-gray-700">Kategori</label>
                                            <select 
                                                value={data.category_id}
                                                onChange={e => setData('category_id', e.target.value)}
                                                className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm py-2"
                                                required>
                                                <option value="">-- Pilih Kategori --</option>
                                                {categories.map(category => (
                                                    <option key={category.id} value={category.id}>
                                                        {category.name}
                                                    </option>
                                                ))}
                                            </select>
                                            {errors.category_id && <div className="text-red-600 text-sm mt-1">{errors.category_id}</div>}
                                        </div>

                                        <div>
                                            <label className="block text-sm font-medium text-gray-700">Deskripsi</label>
                                            <textarea 
                                                value={data.description}
                                                onChange={e => setData('description', e.target.value)}
                                                rows="3"
                                                className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                            </textarea>
                                            {errors.description && <div className="text-red-600 text-sm mt-1">{errors.description}</div>}
                                        </div>
                                    </div>

                                    {/* Right Column */}
                                    <div className="space-y-3">
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                                            <input 
                                                type="number" 
                                                value={data.price}
                                                onChange={e => setData('price', e.target.value)}
                                                min="0"
                                                placeholder="0"
                                                className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm py-2"
                                                required
                                            />
                                            {errors.price && <div className="text-red-600 text-sm mt-1">{errors.price}</div>}
                                        </div>

                                        <div>
                                            <label className="block text-sm font-medium text-gray-700">Stok Awal</label>
                                            <input 
                                                type="number" 
                                                value={data.stock}
                                                onChange={e => setData('stock', e.target.value)}
                                                min="0"
                                                className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm py-2"
                                                required
                                            />
                                            {errors.stock && <div className="text-red-600 text-sm mt-1">{errors.stock}</div>}
                                        </div>

                                        <div>
                                            <label className="block text-sm font-medium text-gray-700 mb-2">Upload Gambar</label>
                                            <div className="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition">
                                                {preview ? (
                                                    <div className="relative">
                                                        <img 
                                                            src={preview} 
                                                            alt="Preview" 
                                                            className="mx-auto h-32 object-cover rounded"
                                                        />
                                                        <button 
                                                            type="button"
                                                            onClick={() => {
                                                                setPreview(null);
                                                                setData('image', null);
                                                            }}
                                                            className="mt-2 text-sm text-red-600 hover:text-red-800">
                                                            Hapus
                                                        </button>
                                                    </div>
                                                ) : (
                                                    <div>
                                                        <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                        </svg>
                                                        <p className="text-sm text-gray-600 mt-2">Click to upload or drag and drop</p>
                                                        <p className="text-xs text-gray-500 mt-1">PNG, JPG, WEBP up to 2MB</p>
                                                    </div>
                                                )}
                                                <input 
                                                    type="file" 
                                                    onChange={handleImageChange}
                                                    accept="image/*"
                                                    className="hidden"
                                                    id="image-upload"
                                                />
                                                <label htmlFor="image-upload" className="cursor-pointer mt-2 inline-block bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 border border-gray-300 rounded-md">
                                                    Pilih Gambar
                                                </label>
                                            </div>
                                            {errors.image && <div className="text-red-600 text-sm mt-1">{errors.image}</div>}
                                        </div>
                                    </div>
                                </div>

                                <div className="flex justify-end gap-3 mt-6 pt-4 border-t">
                                    <Link 
                                        href={route('products.index')}
                                        className="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                        Batal
                                    </Link>
                                    <button 
                                        type="submit"
                                        disabled={processing}
                                        className="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium disabled:opacity-50">
                                        {processing ? 'Menyimpan...' : 'Simpan Produk'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        </AppLayout>
    );
}
