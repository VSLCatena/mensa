import { FormArray, FormGroup } from "@angular/forms";
import { MensaExtraOption } from "../mensa-extra-option.model";
import { MenuItem } from "../menu-item.model";

export class CreateMensaDto {
    public title: string = '';
    public date: string = '';
    public closingTime: string = '';
    public maxUsers: string = '';
    public price: string = '';
    public menu: MenuItem[] = [];
    public extraOptions: MensaExtraOption[] = [];

    public mapForm(form: FormGroup) {
        this.title = form.get('title')!.value;
        this.date = form.get('date')!.value;
        this.closingTime = form.get('closingTime')!.value;
        this.maxUsers = form.get('maxUsers')!.value;
        this.price = form.get('price')!.value;
        this.menu = this.mapMenuItems(form.get('menu')! as FormArray);
        this.extraOptions = this.mapExtraOptions(form.get('extraOptions')! as FormArray);
    }

    private mapMenuItems(formArray: FormArray): MenuItem[] {
        let menuItems: MenuItem[] = [];
        formArray.controls.forEach((control) => {
            menuItems.push(new MenuItem(
                null,
                null,
                control.get('order')!.value,
                control.get('text')!.value
            ));
        });
        return menuItems;
    }

    private mapExtraOptions(formArray: FormArray): MensaExtraOption[] {
        let extraOptions: MensaExtraOption[] = [];
        formArray.controls.forEach((control) => {
            extraOptions.push(new MensaExtraOption(
                null,
                null,
                control.get('description')!.value,
                control.get('price')!.value
            ));
        });
        return extraOptions;
    }

}