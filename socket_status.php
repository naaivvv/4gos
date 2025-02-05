<style>
.thermometer-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.thermometer-container {
    width: 300px;
    height: 40px;
    background: #e7e5e4;
    border-radius: 20px;
    position: relative;
    overflow: hidden;
}

.thermometer {
    width: 100%;
    height: 100%;
    border-radius: 20px;
    overflow: hidden;
    position: relative;
}

.thermometer-fill {
    width: 0%;
    height: 100%;
    background: linear-gradient(87deg, rgba(6,121,210,1) 13%, rgba(49,184,211,1) 65%);
    border-radius: 20px;
    position: absolute;
    left: 0;
    top: 0;
    transition: width 0.5s ease-in-out;
    display: flex;
    justify-content: center;
    align-items: center;
}

.temperature-value {
    color: white;
    font-size: 16px;
    font-weight: bold;
}

.thermometer-ruler {
    width: 300px;
    height: 20px;
    display: flex;
    justify-content: space-between;
    position: relative;
    margin-top: 5px;
}

.thermometer-ruler::before {
    content: "";
    width: 100%;
    height: 2px;
    background: #ccc;
    position: absolute;
    top: 50%;
    left: 0;
}

.thermometer-ruler span {
    font-size: 12px;
    color: #555;
    position: relative;
    top: 5px;
}

.thermometer-ruler span::before {
    content: "";
    width: 2px;
    height: 10px;
    background: #555;
    position: absolute;
    top: -10px;
    left: 50%;
    transform: translateX(-50%);
}
</style>

<div class="text-md bold grid grid-cols-1 lg:grid-cols-2 gap-2 px-6">
                    <div class="p-2">
                        <p class="mt-4 text-lg font-bold flex items-center gap-2">
                            <svg class="h-6 w-6 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M21 8H17.8174M21 12H18M21 16H17.8174M6.18257 8H3M8 6.18257V3M8 21L8 17.8174M12 6V3M12 21V18M16 6.18257V3M16 21V17.8174M6 12H3M6.18257 16H3M10.8 18H13.2C14.8802 18 15.7202 18 16.362 17.673C16.9265 17.3854 17.3854 16.9265 17.673 16.362C18 15.7202 18 14.8802 18 13.2V10.8C18 9.11984 18 8.27976 17.673 7.63803C17.3854 7.07354 16.9265 6.6146 16.362 6.32698C15.7202 6 14.8802 6 13.2 6H10.8C9.11984 6 8.27976 6 7.63803 6.32698C7.07354 6.6146 6.6146 7.07354 6.32698 7.63803C6 8.27976 6 9.11984 6 10.8V13.2C6 14.8802 6 15.7202 6.32698 16.362C6.6146 16.9265 7.07354 17.3854 7.63803 17.673C8.27976 18 9.11984 18 10.8 18ZM10 10H14V14H10V10Z" stroke="#292524" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                            ESP32: 
                            <span id="esp32-status" style="color: #ef4444;">Disconnected</span>
                        </p>
                        <p class="px-8 text-md font-medium text-stone-700"><span id="wifi-ssid">--</span></p>
                        <p class="thin italic px-8 text-sm text-cyan-600"><span id="esp32-ip">--</span></p>
                        <ul class="mt-6">
                            <li class="flex text-center">
                                <svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="#292524" class="bi bi-outlet h-6 w-6 inline">
                                        <g id="SVGRepo_iconCarrier"> 
                                            <path d="M3.34 2.994c.275-.338.68-.494 1.074-.494h7.172c.393 0 .798.156 1.074.494.578.708 1.84 2.534 1.84 5.006 0 2.472-1.262 4.297-1.84 5.006-.276.338-.68.494-1.074.494H4.414c-.394 0-.799-.156-1.074-.494C2.762 12.297 1.5 10.472 1.5 8c0-2.472 1.262-4.297 1.84-5.006zm1.074.506a.376.376 0 0 0-.299.126C3.599 4.259 2.5 5.863 2.5 8c0 2.137 1.099 3.74 1.615 4.374.06.073.163.126.3.126h7.17c.137 0 .24-.053.3-.126.516-.633 1.615-2.237 1.615-4.374 0-2.137-1.099-3.74-1.615-4.374a.376.376 0 0 0-.3-.126h-7.17z"></path> 
                                            <path d="M6 5.5a.5.5 0 0 1 .5.5v1.5a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm4 0a.5.5 0 0 1 .5.5v1.5a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zM7 10v1h2v-1a1 1 0 0 0-2 0z"></path>
                                        </g>
                                </svg>
                                 1 <span id="socket-3-status">--</span>
                            </li>
                            <li class="flex text-center">
                                <svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="#292524" class="bi bi-outlet h-6 w-6 inline">
                                        <g id="SVGRepo_iconCarrier"> 
                                            <path d="M3.34 2.994c.275-.338.68-.494 1.074-.494h7.172c.393 0 .798.156 1.074.494.578.708 1.84 2.534 1.84 5.006 0 2.472-1.262 4.297-1.84 5.006-.276.338-.68.494-1.074.494H4.414c-.394 0-.799-.156-1.074-.494C2.762 12.297 1.5 10.472 1.5 8c0-2.472 1.262-4.297 1.84-5.006zm1.074.506a.376.376 0 0 0-.299.126C3.599 4.259 2.5 5.863 2.5 8c0 2.137 1.099 3.74 1.615 4.374.06.073.163.126.3.126h7.17c.137 0 .24-.053.3-.126.516-.633 1.615-2.237 1.615-4.374 0-2.137-1.099-3.74-1.615-4.374a.376.376 0 0 0-.3-.126h-7.17z"></path> 
                                            <path d="M6 5.5a.5.5 0 0 1 .5.5v1.5a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm4 0a.5.5 0 0 1 .5.5v1.5a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zM7 10v1h2v-1a1 1 0 0 0-2 0z"></path>
                                        </g>
                                </svg>
                                2 <span id="socket-2-status">--</span>
                            </li>
                            <li class="flex text-center">
                                <svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="#292524" class="bi bi-outlet h-6 w-6 inline">
                                        <g id="SVGRepo_iconCarrier"> 
                                            <path d="M3.34 2.994c.275-.338.68-.494 1.074-.494h7.172c.393 0 .798.156 1.074.494.578.708 1.84 2.534 1.84 5.006 0 2.472-1.262 4.297-1.84 5.006-.276.338-.68.494-1.074.494H4.414c-.394 0-.799-.156-1.074-.494C2.762 12.297 1.5 10.472 1.5 8c0-2.472 1.262-4.297 1.84-5.006zm1.074.506a.376.376 0 0 0-.299.126C3.599 4.259 2.5 5.863 2.5 8c0 2.137 1.099 3.74 1.615 4.374.06.073.163.126.3.126h7.17c.137 0 .24-.053.3-.126.516-.633 1.615-2.237 1.615-4.374 0-2.137-1.099-3.74-1.615-4.374a.376.376 0 0 0-.3-.126h-7.17z"></path> 
                                            <path d="M6 5.5a.5.5 0 0 1 .5.5v1.5a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm4 0a.5.5 0 0 1 .5.5v1.5a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zM7 10v1h2v-1a1 1 0 0 0-2 0z"></path>
                                        </g>
                                </svg>
                                3 <span id="socket-1-status">--</span>
                            </li>
                            <li class="flex text-center">
                                <svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="#292524" class="bi bi-outlet h-6 w-6 inline">
                                        <g id="SVGRepo_iconCarrier"> 
                                            <path d="M3.34 2.994c.275-.338.68-.494 1.074-.494h7.172c.393 0 .798.156 1.074.494.578.708 1.84 2.534 1.84 5.006 0 2.472-1.262 4.297-1.84 5.006-.276.338-.68.494-1.074.494H4.414c-.394 0-.799-.156-1.074-.494C2.762 12.297 1.5 10.472 1.5 8c0-2.472 1.262-4.297 1.84-5.006zm1.074.506a.376.376 0 0 0-.299.126C3.599 4.259 2.5 5.863 2.5 8c0 2.137 1.099 3.74 1.615 4.374.06.073.163.126.3.126h7.17c.137 0 .24-.053.3-.126.516-.633 1.615-2.237 1.615-4.374 0-2.137-1.099-3.74-1.615-4.374a.376.376 0 0 0-.3-.126h-7.17z"></path> 
                                            <path d="M6 5.5a.5.5 0 0 1 .5.5v1.5a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm4 0a.5.5 0 0 1 .5.5v1.5a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zM7 10v1h2v-1a1 1 0 0 0-2 0z"></path>
                                        </g>
                                </svg>
                                4 <span id="socket-0-status">--</span>
                            </li>
                        </ul>
                    </div>
                    <div class="p-2 lg:text-right text-left">
                        <h4 class="mt-4 temp-status text-5xl font-bold">--°C</h4>
                        <h3 class="fan-status font-medium">
                            <svg class="h-4 w-4 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <rect x="2" y="2" width="20" height="20" rx="1" stroke="#292524" stroke-width="2"></rect> <rect x="2" y="2" width="20" height="20" rx="10" stroke="#292524" stroke-width="2"></rect> <path d="M14.9999 9.50009C15.4999 9.16675 15.8999 7.80009 15.4999 7.00009C15.0999 6.20009 13.3334 5.66674 13.0001 5.50007M14 10.5001C15.5001 8.5001 14.0001 7.00006 11.5 5.5001C9.95405 4.57263 13.4999 4.00009 16.0001 6.00009C17.8751 7.49994 17 8.50009 14 11.5001V10.5001Z" stroke="#292524" stroke-width="2"></path> <path d="M8.98522 14.402C8.48522 14.7353 8.08522 16.102 8.48522 16.902C8.88522 17.702 10.6517 18.2354 10.9851 18.402M9.98516 13.402C8.48509 15.402 9.98504 16.902 12.4852 18.402C14.0311 19.3295 10.4852 19.902 7.98502 17.902C6.11005 16.4022 6.98516 15.402 9.98516 12.402V13.402Z" stroke="#292524" stroke-width="2"></path> <path d="M9.54159 8.94372C9.20826 8.44372 7.84159 8.04372 7.04159 8.44372C6.24159 8.84372 5.70824 10.6102 5.54157 10.9435M10.5416 9.94366C8.54161 8.44359 7.04156 9.94354 5.54161 12.4437C4.61413 13.9896 4.04159 10.4437 6.04159 7.94352C7.54145 6.06854 8.5416 6.94366 11.5416 9.94366H10.5416Z" stroke="#292524" stroke-width="2"></path> <path d="M14.4436 14.9584C14.7769 15.4584 16.1436 15.8584 16.9436 15.4584C17.7436 15.0584 18.2769 13.2919 18.4436 12.9586M13.4436 13.9584C15.4436 15.4585 16.9436 13.9586 18.4436 11.4584C19.371 9.91249 19.9436 13.4584 17.9436 15.9586C16.4437 17.8336 15.4436 16.9584 12.4436 13.9584H13.4436Z" stroke="#292524" stroke-width="2"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M3.5 5C4.32843 5 5 4.32843 5 3.5C5 2.67157 4.32843 2 3.5 2C2.67157 2 2 2.67157 2 3.5C2 4.32843 2.67157 5 3.5 5Z" stroke="#292524" stroke-width="2"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M3.5 22C4.32843 22 5 21.3284 5 20.5C5 19.6716 4.32843 19 3.5 19C2.67157 19 2 19.6716 2 20.5C2 21.3284 2.67157 22 3.5 22Z" stroke="#292524" stroke-width="2"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M20.5 22C21.3284 22 22 21.3284 22 20.5C22 19.6716 21.3284 19 20.5 19C19.6716 19 19 19.6716 19 20.5C19 21.3284 19.6716 22 20.5 22Z" stroke="#292524" stroke-width="2"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M20.5 5C21.3284 5 22 4.32843 22 3.5C22 2.67157 21.3284 2 20.5 2C19.6716 2 19 2.67157 19 3.5C19 4.32843 19.6716 5 20.5 5Z" stroke="#292524" stroke-width="2"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M12 14C13.1046 14 14 13.1046 14 12C14 10.8954 13.1046 10 12 10C10.8954 10 10 10.8954 10 12C10 13.1046 10.8954 14 12 14Z" stroke="#292524" stroke-width="2"></path> </g></svg>
                            <span>--</span>
                        </h3>
                        <div class="thermometer-wrapper mt-12">
                            <div class="thermometer-container">
                                <div class="thermometer relative">
                                    <div class="thermometer-fill">
                                        <span class="temperature-value absolute left-5">0°C</span>
                                    </div>
                                </div>
                            </div>
                            <div class="thermometer-ruler"></div>
                        </div>

                    </div>
                </div>