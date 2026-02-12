import AppLayout from '@/Layouts/AppLayout';
import { Link, useForm } from '@inertiajs/react';

export default function CategoriesCreate() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        slug: '',
    });

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

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('categories.store'));
    };

    return (
        <AppLayout title="Tambah Kategori">
            <div className="p-6">
                <div className="mb-6 flex items-center gap-2 text-sm text-gray-600">
                    <Link href={route('categories.index')} className="hover:text-gray-900">
                        Kategori
                    </Link>
                    <span>/</span>
                    <span className="text-gray-900">Tambah Baru</span>
                </div>

                <div className="mb-6">
                    <h2 className="text-2xl font-bold text-gray-800">Tambah Kategori Baru</h2>
                    <p className="text-gray-600 mt-1">Buat kategori baru untuk produk</p>
                </div>

                <div className="max-w-2xl bg-white rounded-lg shadow-sm border border-gray-200">
                    <div className="p-6">

                            <form onSubmit={handleSubmit}>
                                <div className="space-y-4">
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700">Nama Kategori</label>
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
                                </div>

                                <div className="flex justify-end gap-3 mt-6 pt-4 border-t">
                                    <Link 
                                        href={route('categories.index')}
                                        className="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                        Batal
                                    </Link>
                                    <button 
                                        type="submit"
                                        disabled={processing}
                                        className="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium disabled:opacity-50">
                                        {processing ? 'Menyimpan...' : 'Simpan Kategori'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        </AppLayout>
    );
}
