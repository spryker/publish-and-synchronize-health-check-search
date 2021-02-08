import { NgModule } from '@angular/core';
import { provideIcons } from '@spryker/icon';

const svg = `
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 21"><g fill-rule="nonzero" fill="none"><circle fill="#F0F0F0" cx="10.082" cy="10.082" r="10.082"/><g fill="#D80027"><path d="M9.643 10.082h10.52c0-.91-.12-1.792-.347-2.63H9.643v2.63zM9.643 4.822h9.04a10.135 10.135 0 00-2.326-2.63H9.643v2.63zM10.082 20.163c2.372 0 4.553-.82 6.275-2.191H3.806a10.038 10.038 0 006.276 2.191zM1.48 15.342h17.204c.495-.809.88-1.693 1.132-2.63H.347c.253.937.637 1.821 1.133 2.63z"/></g><path d="M4.67 1.574h.919l-.855.621.327 1.005-.855-.621-.854.62.282-.867a10.137 10.137 0 00-1.956 2.18h.295l-.544.394c-.085.142-.167.285-.244.431l.26.8-.485-.352c-.12.255-.23.516-.33.782l.286.881h1.057l-.855.62.326 1.006-.854-.621-.512.372C.027 9.236 0 9.655 0 10.082h10.082V0C8.09 0 6.234.578 4.67 1.574zm.39 7.5l-.854-.621-.854.62.326-1.004-.855-.62H3.88l.326-1.006.326 1.005H5.59l-.855.62.327 1.006zm-.326-3.942l.327 1.005-.855-.621-.854.62.326-1.004-.855-.62H3.88l.326-1.005.326 1.004H5.59l-.855.621zm3.943 3.942l-.855-.621-.854.62.326-1.004-.854-.62h1.056l.326-1.006.327 1.005h1.056l-.855.62.327 1.006zM8.35 5.132l.327 1.005-.855-.621-.854.62.326-1.004-.854-.62h1.056l.326-1.005.327 1.004h1.056l-.855.621zm0-2.937L8.677 3.2l-.855-.621-.854.62.326-1.004-.854-.62h1.056L7.822.57l.327 1.004h1.056l-.855.621z" fill="#0052B4"/></g></svg>
`;

@NgModule({
  providers: [provideIcons([IconUnitedStatesModule])],
})
export class IconUnitedStatesModule {
  static icon = 'united-states';
  static svg = svg;
}
