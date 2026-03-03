import React from 'react';
import { Head } from '@inertiajs/react';

export default function Home() {
    return (
        <>
            <Head title="Home" />
            <div className="min-h-screen bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900 text-white">
                <nav className="border-b border-white/10 bg-white/5 backdrop-blur-sm">
                    <div className="container mx-auto flex items-center justify-between px-6 py-4">
                        <a
                            href="#"
                            className="text-2xl font-semibold tracking-tight"
                        >
                            Hotel<span className="text-indigo-400">.</span>
                        </a>
                        <div className="hidden items-center gap-6 md:flex">
                            <a
                                href="#"
                                className="text-sm hover:text-indigo-300"
                            >
                                Home
                            </a>
                            <a
                                href="#about"
                                className="text-sm hover:text-indigo-300"
                            >
                                About
                            </a>
                            <a
                                href="#contact"
                                className="text-sm hover:text-indigo-300"
                            >
                                Contact
                            </a>
                            <button className="shadow-glow ml-4 rounded-lg bg-indigo-500/80 px-4 py-2 hover:bg-indigo-500">
                                Book
                            </button>
                        </div>
                        <div className="md:hidden">
                            <button className="rounded bg-white/5 px-3 py-2">
                                Menu
                            </button>
                        </div>
                    </div>
                </nav>

                <header className="container mx-auto px-6 py-20">
                    <div className="grid grid-cols-1 items-center gap-8 md:grid-cols-2">
                        <div>
                            <h1 className="text-4xl leading-tight font-extrabold md:text-5xl">
                                An elegant stay, reimagined{' '}
                                <span className="text-indigo-400">
                                    with glow
                                </span>
                            </h1>
                            <p className="mt-6 max-w-xl text-gray-300">
                                Experience modern comfort and subtle luxury in
                                our thoughtfully designed rooms. This is a demo
                                hero showcasing a sleek, glowing UI style for
                                the project's Home page.
                            </p>
                            <div className="mt-8 flex gap-4">
                                <a
                                    href="#contact"
                                    className="shadow-glow rounded-lg bg-indigo-500 px-6 py-3 hover:bg-indigo-400"
                                >
                                    Get in Touch
                                </a>
                                <a
                                    href="#about"
                                    className="rounded-lg border border-white/10 px-6 py-3"
                                >
                                    Learn More
                                </a>
                            </div>
                        </div>
                        <div className="relative">
                            <div className="transform overflow-hidden rounded-2xl shadow-2xl transition-transform duration-500 hover:scale-[1.02]">
                                <div className="shadow-glow rounded-2xl bg-gradient-to-tr from-indigo-600 to-pink-500 p-1">
                                    <div className="rounded-xl bg-gray-900 p-6">
                                        <img
                                            src="/build/assets/hero-placeholder.png"
                                            alt="hotel"
                                            className="h-56 w-full rounded-lg object-cover"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div className="absolute -bottom-6 left-4 rounded-lg border border-indigo-700/30 bg-indigo-700/20 px-3 py-2 text-sm backdrop-blur-md">
                                Exclusive Offer
                            </div>
                        </div>
                    </div>
                </header>

                <main className="container mx-auto px-6 pb-20">
                    <section id="about" className="py-16">
                        <div className="mx-auto max-w-4xl text-center">
                            <h2 className="text-3xl font-bold">About Us</h2>
                            <p className="mt-4 text-gray-300">
                                We blend contemporary design with warm
                                hospitality. This about block is part of the
                                demo page to show responsive layouts and subtle
                                glowing accents.
                            </p>
                            <div className="mt-8 grid grid-cols-1 gap-6 md:grid-cols-3">
                                <div className="rounded-xl border border-white/5 bg-white/3 p-6 shadow-sm">
                                    <h3 className="font-semibold">Comfort</h3>
                                    <p className="mt-2 text-sm text-gray-300">
                                        Thoughtful amenities for a restful stay.
                                    </p>
                                </div>
                                <div className="rounded-xl border border-white/5 bg-white/3 p-6 shadow-sm">
                                    <h3 className="font-semibold">Location</h3>
                                    <p className="mt-2 text-sm text-gray-300">
                                        Close to attractions and transport
                                        links.
                                    </p>
                                </div>
                                <div className="rounded-xl border border-white/5 bg-white/3 p-6 shadow-sm">
                                    <h3 className="font-semibold">Service</h3>
                                    <p className="mt-2 text-sm text-gray-300">
                                        Personalised care and 24/7 support.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="contact" className="py-16">
                        <div className="mx-auto max-w-2xl rounded-2xl border border-white/6 bg-white/3 p-8">
                            <h2 className="text-2xl font-bold">Contact Us</h2>
                            <p className="mt-2 text-gray-300">
                                Have questions? Send us a message.
                            </p>
                            <form className="mt-6 grid grid-cols-1 gap-4">
                                <input
                                    className="rounded-lg border border-white/8 bg-transparent p-3"
                                    placeholder="Your name"
                                />
                                <input
                                    className="rounded-lg border border-white/8 bg-transparent p-3"
                                    placeholder="Email"
                                />
                                <textarea
                                    className="rounded-lg border border-white/8 bg-transparent p-3"
                                    placeholder="Message"
                                    rows={4}
                                />
                                <div className="text-right">
                                    <button
                                        type="submit"
                                        className="shadow-glow rounded-lg bg-indigo-500 px-5 py-2"
                                    >
                                        Send Message
                                    </button>
                                </div>
                            </form>
                        </div>
                    </section>
                </main>

                <footer className="border-t border-white/6 py-8">
                    <div className="container mx-auto px-6 text-center text-sm text-gray-400">
                        © {new Date().getFullYear()} Hotel — Demo page
                    </div>
                </footer>
            </div>
        </>
    );
}
