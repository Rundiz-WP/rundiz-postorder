/**
 * Settings JS for Admin > Settings > Rundiz Post Order settings page.
 * 
 * @package rd-postorder
 * @since 1.0.8
 */


class RdPostOrderSettings {


    /**
     * Class constructor.
     */
    constructor() {
        this.#listenClickDangerButton();
    }// constructor


    /**
     * Listen on click danger button and ask for confirmation.
     * 
     * @returns {undefined}
     */
    #listenClickDangerButton() {
        document.addEventListener('click', (event) => {
            let thisTarget = event.target;
            if (thisTarget.closest('.button-danger')) {
                thisTarget = thisTarget.closest('.button-danger');
            } else {
                return ;
            }

            const confirmVal = confirm(RdPostOrderSettingsObj.txtAreYouSure);
            if (!confirmVal) {
                event.preventDefault();
            }
        });
    }// #listenClickDangerButton


}// RdPostOrderSettings


document.addEventListener('DOMContentLoaded', () => {
    new RdPostOrderSettings();
});