                    <div id="controls w-full place-items-center">
                        <!-- Grid Layout -->
                        <div class="grid grid-cols-2 lg:grid-cols-2 gap-4 place-items-center max-w-xs m-auto">
                            <!-- Outlet 1 -->
                            <div class="items-center p-4 bg-stone-50 rounded-md shadow-sm w-36 h-36 relative">
                                <div class="flex items-center justify-between">
                                <svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="#292524" class="bi bi-outlet h-8 w-8"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M3.34 2.994c.275-.338.68-.494 1.074-.494h7.172c.393 0 .798.156 1.074.494.578.708 1.84 2.534 1.84 5.006 0 2.472-1.262 4.297-1.84 5.006-.276.338-.68.494-1.074.494H4.414c-.394 0-.799-.156-1.074-.494C2.762 12.297 1.5 10.472 1.5 8c0-2.472 1.262-4.297 1.84-5.006zm1.074.506a.376.376 0 0 0-.299.126C3.599 4.259 2.5 5.863 2.5 8c0 2.137 1.099 3.74 1.615 4.374.06.073.163.126.3.126h7.17c.137 0 .24-.053.3-.126.516-.633 1.615-2.237 1.615-4.374 0-2.137-1.099-3.74-1.615-4.374a.376.376 0 0 0-.3-.126h-7.17z"></path> <path d="M6 5.5a.5.5 0 0 1 .5.5v1.5a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm4 0a.5.5 0 0 1 .5.5v1.5a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zM7 10v1h2v-1a1 1 0 0 0-2 0z"></path> </g></svg>
                                <label class="switch">
                                    <input type="checkbox" onchange="sendState(3, this.checked ? 1 : 0)">
                                    <span class="slider round"></span>
                                </label>     
                            </div>
                                <div class=" absolute bottom-5 left-5">
                                    <span id="outlet-0" class="mt-2 text-stone-800 font-semibold">Socket 1</span><br>
                                    <span class="text-stone-600 text-xs font-thin italic">Left most outlet</span>
                                </div>
                            </div>

                            <!-- Outlet 2 -->
                            <div class="items-center p-4 bg-stone-50 rounded-md shadow-sm w-36 h-36 relative">
                                <div class="flex items-center justify-between">
                                <svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="#292524" class="bi bi-outlet h-8 w-8"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M3.34 2.994c.275-.338.68-.494 1.074-.494h7.172c.393 0 .798.156 1.074.494.578.708 1.84 2.534 1.84 5.006 0 2.472-1.262 4.297-1.84 5.006-.276.338-.68.494-1.074.494H4.414c-.394 0-.799-.156-1.074-.494C2.762 12.297 1.5 10.472 1.5 8c0-2.472 1.262-4.297 1.84-5.006zm1.074.506a.376.376 0 0 0-.299.126C3.599 4.259 2.5 5.863 2.5 8c0 2.137 1.099 3.74 1.615 4.374.06.073.163.126.3.126h7.17c.137 0 .24-.053.3-.126.516-.633 1.615-2.237 1.615-4.374 0-2.137-1.099-3.74-1.615-4.374a.376.376 0 0 0-.3-.126h-7.17z"></path> <path d="M6 5.5a.5.5 0 0 1 .5.5v1.5a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm4 0a.5.5 0 0 1 .5.5v1.5a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zM7 10v1h2v-1a1 1 0 0 0-2 0z"></path> </g></svg>
                                <label class="switch">
                                    <input type="checkbox" onchange="sendState(2, this.checked ? 1 : 0)">
                                    <span class="slider round"></span>
                                </label>     
                            </div>
                                <div class=" absolute bottom-5 left-5">
                                    <span id="outlet-0" class="mt-2 text-stone-800 font-semibold">Socket 2</span><br>
                                    <span class="text-stone-600 text-xs font-thin italic">Second from the left</span>
                                </div>
                            </div>

                            <!-- Outlet 3 -->
                            <div class="items-center p-4 bg-stone-50 rounded-md shadow-sm w-36 h-36 relative">
                                <div class="flex items-center justify-between">
                                <svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="#292524" class="bi bi-outlet h-8 w-8"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M3.34 2.994c.275-.338.68-.494 1.074-.494h7.172c.393 0 .798.156 1.074.494.578.708 1.84 2.534 1.84 5.006 0 2.472-1.262 4.297-1.84 5.006-.276.338-.68.494-1.074.494H4.414c-.394 0-.799-.156-1.074-.494C2.762 12.297 1.5 10.472 1.5 8c0-2.472 1.262-4.297 1.84-5.006zm1.074.506a.376.376 0 0 0-.299.126C3.599 4.259 2.5 5.863 2.5 8c0 2.137 1.099 3.74 1.615 4.374.06.073.163.126.3.126h7.17c.137 0 .24-.053.3-.126.516-.633 1.615-2.237 1.615-4.374 0-2.137-1.099-3.74-1.615-4.374a.376.376 0 0 0-.3-.126h-7.17z"></path> <path d="M6 5.5a.5.5 0 0 1 .5.5v1.5a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm4 0a.5.5 0 0 1 .5.5v1.5a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zM7 10v1h2v-1a1 1 0 0 0-2 0z"></path> </g></svg>
                                <label class="switch">
                                    <input type="checkbox" onchange="sendState(1, this.checked ? 1 : 0)">
                                    <span class="slider round"></span>
                                </label>     
                            </div>
                                <div class=" absolute bottom-5 left-5">
                                    <span id="outlet-0" class="mt-2 text-stone-800 font-semibold">Socket 3</span><br>
                                    <span class="text-stone-600 text-xs font-thin italic">Third from the series</span>
                                </div>
                            </div>

                            <!-- Outlet 4 -->
                            <div class="items-center p-4 bg-stone-50 rounded-md shadow-sm w-36 h-36 relative">
                                <div class="flex items-center justify-between">
                                <svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="#292524" class="bi bi-outlet h-8 w-8"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M3.34 2.994c.275-.338.68-.494 1.074-.494h7.172c.393 0 .798.156 1.074.494.578.708 1.84 2.534 1.84 5.006 0 2.472-1.262 4.297-1.84 5.006-.276.338-.68.494-1.074.494H4.414c-.394 0-.799-.156-1.074-.494C2.762 12.297 1.5 10.472 1.5 8c0-2.472 1.262-4.297 1.84-5.006zm1.074.506a.376.376 0 0 0-.299.126C3.599 4.259 2.5 5.863 2.5 8c0 2.137 1.099 3.74 1.615 4.374.06.073.163.126.3.126h7.17c.137 0 .24-.053.3-.126.516-.633 1.615-2.237 1.615-4.374 0-2.137-1.099-3.74-1.615-4.374a.376.376 0 0 0-.3-.126h-7.17z"></path> <path d="M6 5.5a.5.5 0 0 1 .5.5v1.5a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm4 0a.5.5 0 0 1 .5.5v1.5a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zM7 10v1h2v-1a1 1 0 0 0-2 0z"></path> </g></svg>
                                <label class="switch">
                                    <input type="checkbox" onchange="sendState(0, this.checked ? 1 : 0)">
                                    <span class="slider round"></span>
                                </label>     
                            </div>
                                <div class=" absolute bottom-5 left-5">
                                    <span id="outlet-0" class="mt-2 text-stone-800 font-semibold">Socket 4</span><br>
                                    <span class="text-stone-600 text-xs font-thin italic">Right most outlet</span>
                                </div>
                            </div>
                        </div>
                    </div>