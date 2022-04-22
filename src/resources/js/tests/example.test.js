import ExampleSum from "../ExampleSum";

test('test sum', ()=>{
    expect(ExampleSum.sum(1)(2)).toBe(3);
});
