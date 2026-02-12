import React from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import { useForm, Link } from '@inertiajs/react';

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        store_name: '',
        phone: '',
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <GuestLayout>
            <div className="mb-6">
                <h2 className="text-2xl font-bold text-gray-900">Daftar Toko Baru</h2>
                <p className="text-sm text-gray-600 mt-1">Buat akun untuk memulai menjual</p>
            </div>

            <form onSubmit={submit}>
                {/* Store Name */}
                <div className="mb-4">
                    <label htmlFor="store_name" className="block font-medium text-sm text-gray-700">
                        Nama Toko
                    </label>
                    <input
                        id="store_name"
                        type="text"
                        name="store_name"
                        value={data.store_name}
                        className="mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2"
                        placeholder="Nama toko Anda"
                        onChange={(e) => setData('store_name', e.target.value)}
                    />
                    {errors.store_name && (
                        <div className="text-sm text-red-600 mt-2">{errors.store_name}</div>
                    )}
                </div>

                {/* Phone */}
                <div className="mb-4">
                    <label htmlFor="phone" className="block font-medium text-sm text-gray-700">
                        Nomor Telepon/WhatsApp
                    </label>
                    <input
                        id="phone"
                        type="text"
                        name="phone"
                        value={data.phone}
                        className="mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2"
                        placeholder="08xxxxxxxxxx atau +628xxxxxxxxxx"
                        onChange={(e) => setData('phone', e.target.value)}
                    />
                    {errors.phone && (
                        <div className="text-sm text-red-600 mt-2">{errors.phone}</div>
                    )}
                </div>

                {/* Owner Name */}
                <div className="mb-4">
                    <label htmlFor="name" className="block font-medium text-sm text-gray-700">
                        Nama Pemilik
                    </label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value={data.name}
                        className="mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2"
                        placeholder="Nama lengkap Anda"
                        onChange={(e) => setData('name', e.target.value)}
                    />
                    {errors.name && (
                        <div className="text-sm text-red-600 mt-2">{errors.name}</div>
                    )}
                </div>

                {/* Email */}
                <div className="mb-4">
                    <label htmlFor="email" className="block font-medium text-sm text-gray-700">
                        Email
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2"
                        placeholder="email@example.com"
                        onChange={(e) => setData('email', e.target.value)}
                    />
                    {errors.email && (
                        <div className="text-sm text-red-600 mt-2">{errors.email}</div>
                    )}
                </div>

                {/* Password */}
                <div className="mb-4">
                    <label htmlFor="password" className="block font-medium text-sm text-gray-700">
                        Password
                    </label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2"
                        placeholder="Minimal 8 karakter"
                        onChange={(e) => setData('password', e.target.value)}
                    />
                    {errors.password && (
                        <div className="text-sm text-red-600 mt-2">{errors.password}</div>
                    )}
                </div>

                {/* Password Confirmation */}
                <div className="mb-6">
                    <label htmlFor="password_confirmation" className="block font-medium text-sm text-gray-700">
                        Konfirmasi Password
                    </label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        value={data.password_confirmation}
                        className="mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2"
                        placeholder="Ulangi password Anda"
                        onChange={(e) => setData('password_confirmation', e.target.value)}
                    />
                    {errors.password_confirmation && (
                        <div className="text-sm text-red-600 mt-2">{errors.password_confirmation}</div>
                    )}
                </div>

                {/* Submit Button */}
                <button
                    type="submit"
                    className="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    disabled={processing}>
                    {processing ? 'Mendaftar...' : 'Daftar'}
                </button>

                {/* Login Link */}
                <div className="text-center mt-4">
                    <p className="text-sm text-gray-600">
                        Sudah punya akun?{' '}
                        <Link
                            href={route('login')}
                            className="text-indigo-600 hover:text-indigo-900 font-medium">
                            Masuk di sini
                        </Link>
                    </p>
                </div>
            </form>
        </GuestLayout>
    );
}
